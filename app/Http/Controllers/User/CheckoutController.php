<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\PostalCode;
use App\Models\Product;
use App\Models\ShippingZone;
use App\Models\TaxSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    private function formatMMK($amount)
    {
        return round($amount / 50) * 50;
    }

    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart')
                ->with('error', 'Your cart is empty.');
        }

        $cart = [];
        $baseSubtotal = 0;

        $taxSetting = TaxSetting::first();
        $taxPercent = $taxSetting?->percent ?? 0;

        foreach ($cartItems as $item) {
            $product = $item->product;
            if (!$product) continue;

            $lineBase = $product->price_usd * $item->qty;
            $baseSubtotal += $lineBase;

            $cart[] = [
                'product' => $product,
                'qty' => $item->qty,
                'subtotal_base' => $lineBase,
            ];
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $currencies = Currency::where('is_active', true)->get()->keyBy('code');

        $subtotal_usd = $baseSubtotal;
        $subtotal_mmk = $baseSubtotal * Currency::getRate('MMK');

        // NEW: shipping zones (Myanmar)
        $shippingZones = ShippingZone::where('country','Myanmar')
            ->where('is_active',true)
            ->get();

        return view('user.pages.checkout.index', compact(
            'cart',
            'subtotal_usd',
            'subtotal_mmk',
            'paymentMethods',
            'currencies',
            'shippingZones',
            'taxPercent'
        ));
    }

    public function placeOrder(Request $request)
    {

        $user = Auth::user();

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Cart is empty.');
        }

        $rules = [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',

            'delivery_type' => 'required|in:delivery,pickup',
            'shipping_zone_id' => [
                Rule::requiredIf($request->delivery_type === 'delivery'),
                Rule::exists('shipping_zones', 'id')
                    ->where(fn ($q) => $q
                        ->where('country', 'Myanmar')
                        ->where('is_active', true)
                    ),
            ],

            'country' => Rule::requiredIf($request->delivery_type === 'delivery'),
            'city' => Rule::requiredIf($request->delivery_type === 'delivery'),
            'street_address' => Rule::requiredIf($request->delivery_type === 'delivery'),
            'postal_code' => Rule::requiredIf($request->delivery_type === 'delivery'),
            'unit' => 'nullable|string|max:100',
            'delivery_note' => 'nullable|string|max:500',

            'payment_method' => 'required|exists:payment_methods,id',
        ];

        // Step 1: Validate base rules first
        $validated = $request->validate($rules);

        $postal = null;
        $postalCity = null;

        // Validate postal code against region (shipping zone)
        if ($request->delivery_type === 'delivery') {

            $postal = PostalCode::where('shipping_zone_id', $validated['shipping_zone_id'])
                ->where('postal_code', trim($validated['postal_code']))
                ->first();

            if (!$postal) {
                return back()
                    ->withErrors([
                        'postal_code' => 'Postal code does not match the selected shipping region.'
                    ])
                    ->withInput();
            }
            $postalCity = $postal->city;

            if (strcasecmp(trim($validated['city']), trim($postalCity)) !== 0) {
                return back()
                    ->withErrors([
                        'city' => 'City does not match the postal code region.'
                    ])
                    ->withInput();
            }
        }

        // Step 2: Now safely get payment method
        $paymentMethod = PaymentMethod::findOrFail($validated['payment_method']);

        // Step 3: If e_wallet, validate screenshot separately
        if ($paymentMethod->type === 'e_wallet' && !$request->hasFile('payment_screenshot')) {
            return back()
                ->withErrors(['payment_screenshot' => 'Payment screenshot is required.'])
                ->withInput();
        }

        // Step 4: Force COD to be delivery only
        if ($paymentMethod->type === 'cod') {
            $request->merge([
                'delivery_type' => 'delivery'
            ]);
        }

        $currencyCode = strtoupper($paymentMethod->currency_code);
        $rate = Currency::getRate($currencyCode);

        // tax from DB (percent)
        $taxPercent = TaxSetting::first()->percent ?? 0;

        $zone = null;
        $zoneName = null;

        if ($request->delivery_type === 'delivery') {
            $zone = ShippingZone::findOrFail($request->shipping_zone_id);
            $zoneName = $zone->name;
        }

        $fullAddress = null;

        if ($request->delivery_type === 'delivery') {
            $addressParts = array_filter([
                $request->street_address,
                $request->unit ? "Unit: ".$request->unit : null,
                $postalCity ?: $request->city,
                $zoneName,
                $request->postal_code,
                $request->country,
            ]);

            $fullAddress = implode(', ', $addressParts);
        }

        try {
            $order = DB::transaction(function () use (
                $user,
                $cartItems,
                $request,
                $currencyCode,
                $rate,
                $paymentMethod,
                $taxPercent,
                $zoneName,
                $postalCity,
                $fullAddress
            ) {

                $subtotalUSD = 0;
                $outOfStock = [];

                /* Check stock first */
                foreach ($cartItems as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);

                    if (!$product || $product->available_stock < $item->qty) {
                        $outOfStock[] = [
                            'id' => $item->product_id,
                            'name' => $product?->display_name ?? 'Some products'
                        ];
                    }
                }

                /* Remove bad items & stop */
                if (!empty($outOfStock)) {
                    // exit transaction cleanly
                    return [
                        'outOfStock' => $outOfStock
                    ];
                }

                /* Reserve stock + calculate subtotal */
                foreach ($cartItems as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);

                    $product->reserved_stock += $item->qty;
                    $product->save();

                    $subtotalUSD += $product->price_usd * $item->qty;
                }

                $mmkRate = Currency::getRate('MMK');
                $subtotalMMK = round($subtotalUSD * $mmkRate);

                $subtotalConverted = $subtotalUSD * $rate;

                // TAX (percent)
                $taxConverted = ($subtotalConverted * $taxPercent) / 100;

                // If MMK → round to whole number
                if ($currencyCode === 'MMK') {
                    $taxConverted = $this->formatMMK($taxConverted);
                }

                // SHIPPING
                $shippingConverted = 0;
                $shippingMMK = 0;

                if ($request->delivery_type === 'delivery') {
                    $zone = ShippingZone::where('id', $request->shipping_zone_id)
                        ->lockForUpdate()
                        ->first();

                    $shippingMMK = $zone->fee_mmk;
                    $mmkRate = Currency::getRate('MMK');

                    $shippingConverted = ($shippingMMK / $mmkRate) * $rate;

                    if ($currencyCode === 'MMK') {
                        $shippingConverted = $this->formatMMK($shippingConverted);
                    }
                }

                $grandTotal = $subtotalConverted + $taxConverted + $shippingConverted;

                if ($currencyCode === 'MMK') {
                    $grandTotal = $this->formatMMK($grandTotal);
                } else {
                    $grandTotal = round($grandTotal, 2);
                }

                $screenshotPath = null;

                if ($paymentMethod->type === 'e_wallet' && $request->hasFile('payment_screenshot')) {
                    $screenshotPath = $request->file('payment_screenshot')
                        ->store('payment_screenshots', 'public');
                }

                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => $user->id,
                    'currency_code' => $currencyCode,

                    'subtotal_usd' => $subtotalUSD,
                    'subtotal_mmk' => $subtotalMMK,

                    'tax' => $taxConverted,
                    'shipping_fee' => $shippingConverted,
                    'grand_total' => $grandTotal,

                    'status' => 'pending_payment',
                    'delivery_type' => $request->delivery_type,

                    'payment_method_id' => $paymentMethod->id,
                    'payment_method'    => $paymentMethod->type,
                    'payment_status' => 'unpaid',

                    'phone' => $request->phone,
                    'shipping_address' => $fullAddress,

                    'payment_screenshot' => $screenshotPath,
                ]);

                foreach ($cartItems as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->display_name,

                        'price_usd' => $product->price_usd,
                        'price_mmk' => $product->price_usd * Currency::getRate('MMK'),

                        'quantity' => $item->qty,
                        'total_price' => ($product->price_usd * $rate) * $item->qty,
                    ]);
                }

                return $order;
            });

            if (isset($order['outOfStock'])) {

                $ids = collect($order['outOfStock'])->pluck('id');
                $names = collect($order['outOfStock'])->pluck('name')->implode(', ');

                Cart::where('user_id', $user->id)
                    ->whereIn('product_id', $ids)
                    ->delete();

                return back()
                    ->withErrors([
                        'stock' => "These products are out of stock and were removed from your cart: $names"
                    ])
                    ->withInput();
            }

            // COD
            if ($paymentMethod->type === 'cod') {

                Cart::where('user_id', $user->id)->delete();

                return redirect()->route('user.orders')
                    ->with('success', 'Order placed successfully. Waiting for admin approval.');
            }

            // STRIPE
            if ($paymentMethod->type === 'stripe') {
                return redirect()->route('stripe.checkout', $order);
            }

            Cart::where('user_id', $user->id)->delete();

            return redirect()->route('user.orders')
                ->with('success', 'Order placed successfully. Please wait for admin approval.');

        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        }
        catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
