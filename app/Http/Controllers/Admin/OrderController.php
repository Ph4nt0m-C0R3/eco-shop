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

    public function paymentProof(Order $order)
    {
        abort_unless($order->payment_screenshot, 404);
        $disk = \Illuminate\Support\Facades\Storage::disk('local');
        if (! $disk->exists($order->payment_screenshot)) {
            // Read existing public proofs through this authorized route during migration.
            $disk = \Illuminate\Support\Facades\Storage::disk('public');
        }
        abort_unless($disk->exists($order->payment_screenshot), 404);
        return $disk->response($order->payment_screenshot, null, ['Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff', 'Content-Security-Policy' => "default-src 'none'; sandbox"]);
    }

    public function ship(Order $order)
    {
        DB::transaction(function () use ($order) {
            $locked = Order::lockForUpdate()->findOrFail($order->id);
            abort_unless($locked->status === 'processing' && $locked->payment_status === 'paid', 409);
            $locked->update(['status' => 'shipped']);
        }, 3);
        return back()->with('success', __('stability.order_shipped'));
    }

    public function approvePayment(Order $order)
    {
        DB::transaction(function () use ($order) {
            $locked = Order::lockForUpdate()->findOrFail($order->id);
            abort_if($locked->payment_method === 'stripe', 409);
            app(\App\Services\OrderLifecycle::class)->commitInventory($locked);
        }, 3);
        return back()->with('success', __('stability.payment_approved'));
    }

    public function rejectPayment(Order $order)
    {
        DB::transaction(function () use ($order) {
            $locked = Order::lockForUpdate()->findOrFail($order->id);
            abort_if($locked->payment_status === 'paid' || $locked->payment_method === 'stripe', 409);
            app(\App\Services\OrderLifecycle::class)->releaseReservation($locked);
        }, 3);
        return back()->with('success', __('stability.payment_rejected'));
    }
}
