<?php

namespace App\Services;

use App\Mail\OrderApprovedMail;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class OrderLifecycle
{
    // Caller must lock the order inside the same transaction as its transition.
    public function commitInventory(Order $order): bool
    {
        if ($order->payment_status === 'paid') {
            return false;
        }
        if ($order->status !== 'pending_payment' || $order->reservation_released_at) {
            throw ValidationException::withMessages(['order' => __('stability.invalid_order_state')]);
        }
        foreach ($order->items()->orderBy('product_id')->get() as $item) {
            $product = Product::lockForUpdate()->find($item->product_id);
            if (! $product || $product->stock < $item->quantity || $product->reserved_stock < $item->quantity) {
                throw ValidationException::withMessages(['order' => __('stability.stock_changed')]);
            }
            $product->decrement('stock', $item->quantity);
            $product->decrement('reserved_stock', $item->quantity);
        }
        $order->update([
            'payment_status' => 'paid', 'status' => 'processing', 'paid_at' => now(),
            'inventory_committed_at' => now(), 'reservation_expires_at' => null,
        ]);
        DB::afterCommit(function () use ($order) {
            try {
                Mail::to($order->user->email)->queue(new OrderApprovedMail($order));
            } catch (\Throwable $e) {
                report($e); // A mail failure must never roll back or repeat payment.
            }
        });
        return true;
    }

    public function releaseReservation(Order $order): bool
    {
        if ($order->status !== 'pending_payment' || $order->payment_status === 'paid' || $order->reservation_released_at) {
            return false;
        }
        foreach ($order->items()->orderBy('product_id')->get() as $item) {
            $product = Product::lockForUpdate()->find($item->product_id);
            if ($product) {
                if ($product->reserved_stock < $item->quantity) {
                    throw ValidationException::withMessages(['order' => __('stability.stock_changed')]);
                }
                $product->decrement('reserved_stock', $item->quantity);
            }
        }
        $order->update([
            'status' => 'cancelled', 'payment_status' => 'failed',
            'reservation_released_at' => now(), 'reservation_expires_at' => null,
        ]);
        return true;
    }
}
