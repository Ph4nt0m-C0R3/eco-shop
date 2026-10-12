<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripePayments
{
    public function minorAmount(Order $order): int
    {
        // These are the only card currencies supported by EcoShop's payment settings.
        // Stripe charges use two decimal minor units for USD and MMK.
        if (! in_array(strtoupper($order->currency_code), ['USD', 'MMK'], true)) {
            throw ValidationException::withMessages(['payment_method' => __('stability.unsupported_currency')]);
        }
        return (int) round($order->grand_total * 100);
    }

    public function assertSession(Order $order, object $session): void
    {
        if ($order->payment_method !== 'stripe'
            || (string) ($session->metadata->order_id ?? '') !== (string) $order->id
            || strtolower($order->currency_code) !== strtolower($session->currency ?? '')
            || (int) ($session->amount_total ?? -1) !== $this->minorAmount($order)
            || (bool) ($session->livemode ?? false) !== str_contains((string) config('services.stripe.secret'), '_live_')) {
            throw ValidationException::withMessages(['payment' => __('stability.invalid_payment')]);
        }
    }

    public function expire(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            $locked = Order::lockForUpdate()->findOrFail($order->id);
            if ($locked->status !== 'pending_payment' || $locked->payment_status === 'paid') {
                return false;
            }
            if ($locked->stripe_session_id) {
                if (! config('services.stripe.secret')) {
                    return false;
                }
                Stripe::setApiKey(config('services.stripe.secret'));
                $session = Session::retrieve($locked->stripe_session_id);
                $this->assertSession($locked, $session);
                if ($session->payment_status === 'paid') {
                    return app(OrderLifecycle::class)->commitInventory($locked);
                }
                if ($session->status === 'open') {
                    $session = Session::expire($session->id);
                }
                if ($session->status !== 'expired') {
                    return false; // Complete asynchronous payments await their webhook.
                }
            }
            return app(OrderLifecycle::class)->releaseReservation($locked);
        }, 3);
    }
}
