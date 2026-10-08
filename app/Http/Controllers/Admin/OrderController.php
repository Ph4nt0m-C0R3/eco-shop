<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderApprovedMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $query = Order::with('user');

        // SEARCH
        if (request('search')) {
            $search = request('search');

            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%$search%")
                ->orWhereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%$search%");
                });
            });
        }

        // FILTER STATUS
        if (request('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        // FILTER PAYMENT
        if (request('payment') && request('payment') !== 'all') {
            $query->where('payment_status', request('payment'));
        }

        // SORT
        $sort = request('sort', 'latest');

        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'total_high') {
            $query->orderBy('grand_total', 'desc');
        } elseif ($sort === 'total_low') {
            $query->orderBy('grand_total', 'asc');
        } else {
            $query->latest();
        }

        $orders = $query->paginate(20);

        // AJAX RESPONSE
        if (request()->ajax()) {
            return view('admin.orders.partials.table', compact('orders'))->render();
        }

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');

        return view('admin.orders.show', compact('order'));
    }

    public function ship(Order $order)
    {
        if (
            $order->status !== 'processing' ||
            $order->payment_status !== 'paid'
        ) {
            return back();
        }

        $order->update([
            'status' => 'shipped'
        ]);

        return back()->with('success', 'Order shipped.');
    }

    public function approvePayment(Order $order)
    {
        if ($order->payment_status === 'paid') return back();
        if ($order->status === 'cancelled') return back();

        DB::transaction(function () use ($order) {

            foreach ($order->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);

                if ($product->available_stock < 0) {
                    throw new \Exception('Stock error.');
                }

                $product->stock -= $item->quantity;
                $product->reserved_stock -= $item->quantity;
                $product->save();
            }

            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'paid_at' => now()
            ]);
        });

        Mail::to($order->user->email)->send(new OrderApprovedMail($order));

        return back()->with('success', 'Payment approved & stock deducted.');
    }

    public function rejectPayment(Order $order)
    {
        if ($order->status === 'cancelled') return back();

        DB::transaction(function () use ($order) {

            foreach ($order->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                $product->reserved_stock -= $item->quantity;
                $product->save();
            }

            $order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled'
            ]);
        });

        return back()->with('error', 'Payment rejected.');
    }

}
