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
        abort_unless((int) $order->user_id === (int) Auth::id(), 403);
        $released = DB::transaction(function () use ($order) {
            $locked = Order::lockForUpdate()->findOrFail($order->id);
            abort_unless($locked->payment_method === 'cod', 409);
            return app(\App\Services\OrderLifecycle::class)->releaseReservation($locked);
        }, 3);
        return redirect()->route('user.orders')->with(
            $released ? 'success' : 'error',
            $released ? __('stability.order_cancelled') : __('stability.invalid_order_state')
        );
    }
}
