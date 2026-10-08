<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $query = Order::with('items')
            ->where('user_id', Auth::id());

        $totalOrders = $query->count();

        // SEARCH
        if (request('search')) {
            $search = request('search');

            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%$search%")
                ->orWhere('payment_method', 'like', "%$search%");
            });
        }

        // STATUS FILTER
        if (request('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        // PAYMENT FILTER
        if (request('payment') && request('payment') !== 'all') {
            $query->where('payment_status', request('payment'));
        }

        // SORT
        if (request('sort') === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $orders = $query->paginate(8);

        // AJAX return partial only
        if (request()->ajax()) {
            return view('user.pages.orders.partials.list', compact('orders'))->render();
        }

        return view('user.pages.orders.index', compact('orders', 'totalOrders'));
    }

    public function show($id)
    {
        $order = Order::with('items')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.pages.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        // Security: only owner
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow COD + unpaid + pending_payment
        if (
            $order->payment_method === 'cod'
            && $order->payment_status === 'unpaid'
            && $order->status === 'pending_payment'
        ) {

            DB::transaction(function () use ($order) {

                // Restore stock
                foreach ($order->items as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);
                    if ($product) {
                        $product->reserved_stock -= $item->quantity;
                        $product->save();
                    }
                }

                $order->update([
                    'status' => 'cancelled'
                ]);
            });

            return redirect()->route('user.orders')
                ->with('success', 'Order cancelled successfully.');
        }

        return back()->with('error', 'This order cannot be cancelled.');
    }
}
