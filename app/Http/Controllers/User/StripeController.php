<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderLifecycle;
use App\Services\StripePayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeController extends Controller
{
    private function authorizeOrder(Order $order): void
    {
        abort_unless((int) $order->user_id === (int) auth()->id(), 403);
    }

    public function checkout(Order $order, StripePayments $payments)
    {
        $this->authorizeOrder($order);
        if (! config('services.stripe.secret')) {
            return redirect()->route('user.orders')->with('error', __('stability.payment_unavailable'));
        }
        Stripe::setApiKey(config('services.stripe.secret'));
        try {
            $url = DB::transaction(function () use ($order, $payments) {
                $locked = Order::lockForUpdate()->findOrFail($order->id);
                if ($locked->payment_method !== 'stripe' || $locked->status !== 'pending_payment'
                    || $locked->payment_status === 'paid' || $locked->reservation_released_at) {
                    throw ValidationException::withMessages(['order' => __('stability.invalid_order_state')]);
                }
                if ($locked->stripe_session_id) {
                    $existing = Session::retrieve($locked->stripe_session_id);
                    $payments->assertSession($locked, $existing);
                    if ($existing->status === 'open') {
                        return $existing->url;
                    }
                    throw ValidationException::withMessages(['order' => __('stability.invalid_order_state')]);
                }
                $expires = now()->addMinutes(30);
                $session = Session::create([
                    'payment_method_types' => ['card'], 'mode' => 'payment',
                    'line_items' => [[
                        'price_data' => [
                            'currency' => strtolower($locked->currency_code),
                            'product_data' => ['name' => 'EcoShop Order #'.$locked->order_number],
                            'unit_amount' => $payments->minorAmount($locked),
                        ], 'quantity' => 1,
                    ]],
                    'success_url' => route('stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('stripe.cancel', $locked),
                    'metadata' => ['order_id' => $locked->id],
                    'expires_at' => $expires->timestamp,
                ], ['idempotency_key' => 'ecoshop-order-'.$locked->id]);
                $locked->update(['stripe_session_id' => $session->id, 'reservation_expires_at' => $expires]);
                return $session->url;
            }, 3);
            return redirect()->away($url);
        } catch (ValidationException $e) {
            return redirect()->route('user.orders')->withErrors($e->errors());
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('user.orders')->with('error', __('stability.payment_unavailable'));
        }
    }

    public function success(Request $request)
    {
        $order = Order::where('user_id', auth()->id())
            ->where('stripe_session_id', $request->query('session_id'))->first();
        $paid = $request->filled('session_id') && $order?->payment_status === 'paid';
        return redirect()->route('user.orders')->with(
            $paid ? 'success' : 'warning',
            $paid ? __('stability.payment_confirmed') : __('stability.payment_pending')
        );
    }

    public function cancel(Order $order)
    {
        $this->authorizeOrder($order);
        // A browser return cannot decide payment status or release provider reservations.
        return redirect()->route('user.orders')->with('warning', __('stability.payment_pending'));
    }

    public function webhook(Request $request, StripePayments $payments, OrderLifecycle $lifecycle)
    {
        $secret = config('services.stripe.webhook_secret');
        if (! $secret) {
            return response('Webhook not configured', 503);
        }
        try {
            $event = Webhook::constructEvent($request->getContent(), $request->header('Stripe-Signature'), $secret);
        } catch (\Throwable $e) {
            return response('Invalid signature', 400);
        }
        if (! in_array($event->type, ['checkout.session.completed', 'checkout.session.async_payment_succeeded',
            'checkout.session.expired', 'checkout.session.async_payment_failed'], true)) {
            return response('Event ignored', 200);
        }
        try {
            DB::transaction(function () use ($event, $payments, $lifecycle) {
                $session = $event->data->object;
                $order = Order::where('stripe_session_id', $session->id)->lockForUpdate()->first();
                if (! $order || DB::table('payment_events')->where('provider_event_id', $event->id)->exists()) {
                    return;
                }
                $payments->assertSession($order, $session);
                if (in_array($event->type, ['checkout.session.completed', 'checkout.session.async_payment_succeeded'], true)
                    && $session->payment_status === 'paid') {
                    $lifecycle->commitInventory($order);
                } elseif (in_array($event->type, ['checkout.session.expired', 'checkout.session.async_payment_failed'], true)) {
                    $lifecycle->releaseReservation($order);
                }
                DB::table('payment_events')->insert([
                    'provider_event_id' => $event->id, 'order_id' => $order->id, 'type' => $event->type,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }, 3);
        } catch (ValidationException $e) {
            report($e);
            return response('Payment does not match order', 400);
        }
        return response('Webhook handled', 200);
    }
}
