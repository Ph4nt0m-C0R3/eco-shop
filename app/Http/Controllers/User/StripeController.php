<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Mail\OrderApprovedMail;
use Illuminate\Support\Facades\Mail;

class StripeController extends Controller
{
    public function checkout(Order $order)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $currency = strtolower($order->currency_code); // usd or mmk

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',

            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => 'EcoShop Order #' . $order->order_number,
                    ],
                    'unit_amount' => (int) round($order->grand_total * 100),
                ],
                'quantity' => 1,
            ]],

            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel', $order),

            'metadata' => [
                'order_id' => $order->id,
            ],

            'expires_at' => now()->addMinutes(30)->timestamp,
        ]);

        $order->update([
            'stripe_session_id' => $session->id
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        return redirect()->route('user.orders')
            ->with('success', 'Payment successful!');
    }

    public function cancel(Order $order)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        if (!$order->stripe_session_id) {
            return redirect()->route('user.orders')
                ->with('error', 'Invalid payment session.');
        }

        $session = Session::retrieve($order->stripe_session_id);

        // If already paid, do nothing
        if ($session->payment_status === 'paid') {
            return redirect()->route('user.orders')
                ->with('success', 'Payment already completed.');
        }

        // Mark as payment_failed instead of cancelled
        if ($order->payment_status === 'unpaid') {
            $order->update([
                'status' => 'pending_payment',
                'payment_status' => 'failed'
            ]);
        }

        return redirect()->route('user.orders')
            ->with('error', 'Payment was cancelled. You can retry payment.');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $secret
            );
        } catch (\Exception $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {

            $session = $event->data->object;

            $order = Order::where('stripe_session_id', $session->id)->first();

            if ($order && $order->payment_status !== 'paid') {

                DB::transaction(function () use ($order) {

                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                        'paid_at' => now(),
                    ]);

                    foreach ($order->items as $item) {
                        $product = Product::lockForUpdate()->find($item->product_id);
                        if ($product) {
                            $product->stock -= $item->quantity;
                            $product->reserved_stock -= $item->quantity;
                            $product->save();
                        }
                    }

                    Cart::where('user_id', $order->user_id)->delete();
                });

                Mail::to($order->user->email)->send(new OrderApprovedMail($order));
            }
        }

        return response('Webhook handled', 200);
    }
}
