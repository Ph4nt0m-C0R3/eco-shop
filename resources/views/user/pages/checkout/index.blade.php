@extends('user.layouts.master')

@section('content')

<style>
    .checkout-wrapper {
        background: #f7f8fc;
        min-height: 100vh;
        padding: 50px 0;
        margin-top: 120px;
    }

    .checkout-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 35px rgba(0,0,0,0.06);
        padding: 28px;
    }

    .checkout-title {
        font-weight: 700;
        margin-bottom: 25px;
        font-size: 20px;
    }

    .form-label {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border-radius: 12px;
        padding: 12px 14px;
        border: 1px solid #e3e6f0;
        font-size: 14px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6c63ff;
        box-shadow: 0 0 0 0.15rem rgba(108, 99, 255, 0.18);
    }

    .option-box {
        border: 1px solid #e3e6f0;
        border-radius: 14px;
        padding: 14px;
        cursor: pointer;
        transition: .2s;
        min-width: 140px;
        text-align: center;
        background: #fff;
    }

    .option-box input {
        display: none;
    }

    .option-box.active {
        border-color: #6c63ff;
        background: #f4f3ff;
        transform: translateY(-2px);
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
        color: #333;
    }

    .summary-item span:first-child {
        color: #555;
    }

    .summary-total {
        font-weight: 800;
        font-size: 18px;
        color: #111;
    }

    .btn-confirm {
        background: linear-gradient(90deg, #6c63ff, #5848e5);
        color: white;
        border-radius: 12px;
        padding: 12px 22px;
        border: none;
        font-weight: 600;
    }

    .btn-back {
        border-radius: 12px;
        padding: 12px 22px;
        font-weight: 600;
    }

    .checkout-section-title {
        font-weight: 700;
        margin-bottom: 15px;
        font-size: 15px;
        color: #333;
    }

    .divider {
        height: 1px;
        background: #eceef5;
        margin: 18px 0;
    }

    .payment-form {
        animation: fadeIn .25s ease-in-out;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(5px);}
        to {opacity: 1; transform: translateY(0);}
    }

    /* Better spacing for Myanmar language */
    html[lang="mm"] body,
    html[lang="my"] body {
        line-height: 1.8;
        word-spacing: 2px;
    }

    /* Order summary item fix */
    .summary-item span:first-child {
        max-width: 65%;
        line-height: 1.7;
        white-space: normal;
        word-break: break-word;
    }

    /* When mmk is shown (usually longer text) */
    .mmk-price {
        font-weight: 600;
    }

    /* ===== Ultra Small Devices (≤ 425px) ===== */
    @media (max-width: 425px) {

        /* Stack checkout buttons vertically */
        .checkout-card .d-flex.justify-content-between {
            flex-direction: column;
            gap: 12px;
        }

        .btn-back,
        .btn-confirm {
            width: 100%;
            text-align: center;
            padding: 14px;
            font-size: 14px;
        }

        .btn-back i,
        .btn-confirm i {
            margin-right: 6px;
        }
    }
</style>

@php
    $tax_usd = ($subtotal_usd * $taxPercent) / 100;
    $shipping_usd = 0;
    $total_usd = $subtotal_usd + $tax_usd + $shipping_usd;

    function round50($amount) {
        return round($amount / 50) * 50;
    }

    $tax_mmk = round50(($subtotal_mmk * $taxPercent) / 100);
    $shipping_mmk = 0;
    $total_mmk = round50($subtotal_mmk + $tax_mmk + $shipping_mmk);
@endphp

<div class="checkout-wrapper">
    <div class="container">
        <div class="row g-4">

            <!-- LEFT -->
            <div class="col-lg-8">
                <div class="checkout-card">

                    <h4 class="checkout-title">
                        <i class="fa-solid fa-credit-card me-2 text-primary"></i>
                        {{ __('checkout.title') }}
                    </h4>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>{{ __('checkout.fix_errors') }}</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li style="font-size:14px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('checkout.place') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="currency" id="currencyInput"
                               value="{{ strtolower($paymentMethods->first()->currency_code ?? 'usd') }}">

                        <!-- CUSTOMER INFO -->
                        <div class="checkout-section-title">
                            <i class="fa-solid fa-user me-2 text-secondary"></i>
                            {{ __('checkout.customer_info') }}
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('checkout.full_name') }}</label>
                                <input type="text"
                                       name="full_name"
                                       class="form-control"
                                       value="{{ old('full_name', auth()->user()->name) }}"
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('checkout.email') }}</label>
                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       value="{{ old('email', auth()->user()->email) }}"
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('checkout.phone') }}</label>
                                <input type="text"
                                       name="phone"
                                       class="form-control"
                                       value="{{ old('phone', auth()->user()->phone) }}"
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('checkout.company') }}</label>
                                <input type="text"
                                       name="company"
                                       class="form-control"
                                       value="{{ old('company') }}">
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- PAYMENT METHODS -->
                        <div class="checkout-section-title">
                            <i class="fa-solid fa-wallet me-2 text-secondary"></i>
                            {{ __('checkout.select_payment') }}
                        </div>

                        <div class="d-flex gap-3 flex-wrap mb-3" id="paymentTabs">
                            @foreach($paymentMethods as $index => $method)
                                <div class="option-box payment-tab"
                                     data-type="{{ $method->type }}"
                                     data-target="{{ $method->id }}"
                                     data-currency="{{ strtolower($method->currency_code) }}"
                                     data-id="{{ $method->id }}">

                                    <input type="radio"
                                        name="payment_method"
                                        value="{{ $method->id }}"
                                        @php
                                            $defaultMethod = old('payment_method');

                                            if (!$defaultMethod) {
                                                $cod = $paymentMethods->firstWhere('type', 'cod');
                                                $defaultMethod = $cod?->id ?? null;
                                            }
                                        @endphp {{ $defaultMethod == $method->id ? 'checked' : '' }}>

                                    <img src="{{ asset('storage/'.$method->icon) }}"
                                         style="width:45px;height:45px;object-fit:contain;"
                                         class="mb-2">

                                    <div style="font-size:14px;font-weight:700;">
                                        {{ $method->display_name }}
                                    </div>

                                    <div style="font-size:12px;color:#666;">
                                        {{ strtoupper($method->currency_code) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- PAYMENT FORM SECTION -->
                        <div id="paymentForms" class="mb-4">
                            @foreach($paymentMethods as $index => $method)

                                <div class="payment-form d-none" data-type="{{ $method->type }}"
                                     id="form-{{ $method->id }}">

                                    @if($method->type === 'cod')
                                        <div class="p-3 rounded-3"
                                            style="background:#e9f9ef;border:1px solid #b9efc8;color:#157347;font-weight:600;">
                                            <i class="fa-solid fa-truck me-2"></i>
                                            {{ __('checkout.cod_text') }}
                                        </div>

                                    @elseif($method->type === 'stripe')
                                        <div class="p-3 rounded-3"
                                            style="background:#e7f1ff;border:1px solid #b6d4fe;color:#084298;font-weight:600;">
                                            <i class="fa-brands fa-cc-stripe me-2"></i>
                                            {{ __('checkout.stripe_text') }}
                                        </div>

                                    @else

                                        <div class="border rounded-3 p-3">
                                            <div class="row g-3 align-items-center">

                                                <div class="col-md-7">
                                                    <div class="mb-2">
                                                        <strong>{{ __('checkout.account_name') }}</strong>
                                                        {{ $method->account_name ?? '-' }}
                                                    </div>

                                                    <div class="mb-2">
                                                        <strong>{{ __('checkout.account_number') }}</strong>
                                                        {{ $method->account_number ?? '-' }}
                                                    </div>

                                                    <div class="mb-0">
                                                        <strong>{{ __('checkout.currency') }}</strong>
                                                        {{ strtoupper($method->currency_code) }}
                                                    </div>
                                                </div>

                                                <div class="col-md-5 text-center">
                                                    @if($method->qr_image)
                                                        <img src="{{ asset('storage/'.$method->qr_image) }}"
                                                             style="max-width:200px;border-radius:14px;">
                                                    @else
                                                        <small class="text-muted">{{ __('checkout.no_qr') }}</small>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>

                                        @if($method->type === 'e_wallet')
                                            <div class="p-3 rounded-3 my-3"
                                                style="background:#fff3cd;border:1px solid #ffe69c;color:#664d03;font-weight:600;">
                                                <i class="fa-solid fa-circle-exclamation me-2"></i>
                                                {{ __('checkout.ewallet_notice', ['name' => $method->name_en]) }}
                                            </div>

                                            <div class="mt-3">
                                                <img id="preview-{{ $method->id }}"
                                                    src="#"
                                                    class="my-3 d-none payment-preview-img"
                                                    style="max-width:200px;border-radius:12px;border:1px solid #ddd;">

                                                <input type="file"
                                                    name="payment_screenshot"
                                                    accept="image/*"
                                                    class="form-control payment-screenshot-input"
                                                    data-preview="preview-{{ $method->id }}">

                                                <label class="form-label">{{ __('checkout.upload_screenshot') }}</label>
                                            </div>
                                        @endif
                                    @endif

                                </div>

                            @endforeach
                        </div>

                        <div class="divider"></div>

                        <!-- DELIVERY OPTION -->
                        <div class="checkout-section-title">
                            <i class="fa-solid fa-truck-fast me-2 text-secondary"></i>
                            {{ __('checkout.delivery_option') }}
                        </div>

                        <div class="d-flex gap-3 flex-wrap mb-3" id="deliveryTabs">

                            <div class="option-box delivery-tab active" data-type="delivery">
                                <input type="radio"
                                    name="delivery_type"
                                    value="delivery"
                                    {{ old('delivery_type','delivery') == 'delivery' ? 'checked' : '' }}>
                                <i class="fa-solid fa-truck-fast mb-2" style="font-size:22px;"></i>
                                <div style="font-size:14px;font-weight:700;">{{ __('checkout.delivery') }}</div>
                                <div style="font-size:12px;color:#666;">{{ __('checkout.shipping_fee') }}</div>
                            </div>

                            <div class="option-box delivery-tab" data-type="pickup">
                                <input type="radio"
                                    name="delivery_type"
                                    value="pickup"
                                    {{ old('delivery_type') == 'pickup' ? 'checked' : '' }}>
                                <i class="fa-solid fa-store mb-2" style="font-size:22px;"></i>
                                <div style="font-size:14px;font-weight:700;">{{ __('checkout.pickup') }}</div>
                                <div style="font-size:12px;color:#666;">{{ __('checkout.no_shipping_fee') }}</div>
                            </div>

                        </div>

                        <div class="divider"></div>

                        <!-- SHIPPING ADDRESS -->
                        <div class="checkout-section-title">
                            <i class="fa-solid fa-location-dot me-2 text-secondary"></i>
                            {{ __('checkout.shipping_address') }}
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('checkout.country') }}</label>
                                <select name="country" class="form-select" required>
                                    <option value="Myanmar">{{ __('checkout.available_country') }}</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('checkout.city') }}</label>
                                <input type="text"
                                       name="city"
                                       class="form-control"
                                       value="{{ old('city') }}">
                            </div>

                            <div class="col-md-6" id="shippingZoneWrapper">
                                <label class="form-label">{{ __('checkout.shipping_zone') }}</label>
                                <select name="shipping_zone_id"
                                        id="shippingZoneSelect"
                                        class="form-select">

                                    <option value="">{{ __('checkout.select_region') }}</option>

                                    @foreach($shippingZones as $zone)
                                        <option value="{{ $zone->id }}"
                                            {{ old('shipping_zone_id') == $zone->id ? 'selected' : '' }}
                                            data-fee-mmk="{{ $zone->fee_mmk }}">
                                            {{ $zone->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('checkout.postal_code') }}</label>
                                <input type="text"
                                       name="postal_code"
                                       class="form-control"
                                       value="{{ old('postal_code') }}">
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">{{ __('checkout.street_address') }}</label>
                                <input type="text"
                                       name="street_address"
                                       class="form-control"
                                       value="{{ old('street_address') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">{{ __('checkout.unit') }}</label>
                                <input type="text"
                                       name="unit"
                                       class="form-control"
                                       value="{{ old('unit') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">{{ __('checkout.delivery_note') }}</label>
                                <textarea name="delivery_note"
                                          class="form-control"
                                          rows="3"
                                          placeholder="{{ __('checkout.delivery_note_placeholder') }}">{{ old('delivery_note') }}</textarea>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- BUTTONS -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('user.cart') }}" class="btn btn-outline-secondary btn-back">
                                <i class="fa-solid fa-arrow-left me-2"></i>
                                {{ __('checkout.back_cart') }}
                            </a>

                            <button type="submit" class="btn-confirm">
                                <i class="fa-solid fa-lock me-2"></i>
                                {{ __('checkout.place_order') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-lg-4">
                <div class="checkout-card">
                    <h5 class="mb-4 fw-bold">
                        <i class="fa-solid fa-receipt me-2 text-primary"></i>
                        {{ __('checkout.order_summary') }}
                    </h5>

                    @foreach($cart as $item)
                        <div class="summary-item">
                            <span>
                                {{ $item['product']->display_name }} × {{ $item['qty'] }}
                            </span>

                            {{-- USD --}}
                            <span class="usd-price">
                                ${{ number_format($item['product']->price_usd * $item['qty'], 2) }}
                            </span>

                            {{-- MMK --}}
                            <span class="mmk-price d-none">
                                {{ number_format(($item['product']->price_usd * $item['qty']) * \App\Models\Currency::getRate('MMK')) }} Ks.
                            </span>
                        </div>
                    @endforeach

                    <hr>

                    <div class="summary-item">
                        <span>{{ __('checkout.subtotal') }}</span>

                        <span class="usd-price" id="subtotalUsd" data-value="{{ $subtotal_usd }}">
                            ${{ number_format($subtotal_usd,2) }}
                        </span>

                        <span class="mmk-price d-none" id="subtotalMmk" data-value="{{ $subtotal_mmk }}">
                            {{ number_format($subtotal_mmk) }} Ks.
                        </span>
                    </div>

                    <div class="summary-item">
                        <span>
                            {{ __('checkout.tax') }} ({{ rtrim(rtrim(number_format($taxPercent, 2), '0'), '.') }}%)
                        </span>

                        <span class="usd-price" id="taxUsd" data-value="{{ $tax_usd }}">
                            ${{ number_format($tax_usd,2) }}
                        </span>

                        <span class="mmk-price d-none" id="taxMmk" data-value="{{ $tax_mmk }}">
                            {{ number_format($tax_mmk) }} Ks.
                        </span>
                    </div>

                    <div class="summary-item" id="shippingRow">
                        <span>{{ __('checkout.shipping') }}</span>

                        <span class="usd-price" id="shippingUsd" data-value="{{ $shipping_usd }}">
                            ${{ number_format($shipping_usd,2) }}
                        </span>

                        <span class="mmk-price d-none" id="shippingMmk" data-value="{{ $shipping_mmk }}">
                            {{ number_format($shipping_mmk) }} Ks.
                        </span>
                    </div>

                    <hr>

                    <div class="summary-item summary-total">
                        <span>{{ __('checkout.total') }}</span>

                        <span class="usd-price" id="totalUsd">
                            ${{ number_format($total_usd,2) }}
                        </span>

                        <span class="mmk-price d-none" id="totalMmk">
                            {{ number_format($total_mmk) }} Ks.
                        </span>
                    </div>

                    <div class="mt-4">
                        <small class="text-muted">
                            {{ __('checkout.terms_notice') }}
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function roundTo50(amount) {
        return Math.round(amount / 50) * 50;
    }

    const mmkRate = {{ \App\Models\Currency::getRate('MMK') }};

    document.addEventListener("DOMContentLoaded", function () {

        const paymentTabs = document.querySelectorAll('.payment-tab');
        const currencyInput = document.getElementById('currencyInput');

        const deliveryTabs = document.querySelectorAll(".delivery-tab");
        const shippingRow = document.getElementById("shippingRow");

        const zoneSelect = document.getElementById("shippingZoneSelect");

        // delivery click
        const shippingZoneWrapper = document.getElementById("shippingZoneWrapper");

        let currentShippingMMK = 0;

        const taxPercent = {{ $taxPercent }};

        function showCurrency(currency) {
            if (currency === 'mmk') {
                document.querySelectorAll('.usd-price').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.mmk-price').forEach(el => el.classList.remove('d-none'));
            } else {
                document.querySelectorAll('.mmk-price').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.usd-price').forEach(el => el.classList.remove('d-none'));
            }
        }

        function isCODSelected() {
            const checked = document.querySelector("input[name='payment_method']:checked");
            if (!checked) return false;

            const tab = checked.closest(".payment-tab");
            const formId = tab.dataset.target;

            // find payment method form and check if it's COD by checking content
            const form = document.getElementById("form-" + formId);
            return form && form.dataset.type === "cod";
        }

        function updateShippingZoneText(currency) {
            Array.from(zoneSelect.options).forEach(option => {
                const feeMMK = parseFloat(option.dataset.feeMmk || 0);
                if (!feeMMK) return;

                if (currency === 'usd') {
                    const usd = feeMMK / mmkRate;
                    option.textContent = option.textContent.split("(")[0].trim() +
                        " ($" + usd.toFixed(2) + ")";
                } else {
                    option.textContent = option.textContent.split("(")[0].trim() +
                        " (" + feeMMK.toLocaleString() + " Ks.)";
                }
            });
        }

        function switchPaymentTab(tab) {

            paymentTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            // Disable all forms + inputs first
            document.querySelectorAll('.payment-form').forEach(f => {
                f.classList.add('d-none');

                f.querySelectorAll("input, select, textarea").forEach(input => {
                    input.disabled = true;
                });
            });

            // Enable only selected form
            const targetId = "form-" + tab.dataset.target;
            const targetForm = document.getElementById(targetId);

            if (targetForm) {
                targetForm.classList.remove('d-none');

                targetForm.querySelectorAll("input, select, textarea").forEach(input => {
                    input.disabled = false;
                });
            }

            // If COD selected, disable pickup
            const pickupTab = document.querySelector('.delivery-tab[data-type="pickup"]');
            const deliveryTab = document.querySelector('.delivery-tab[data-type="delivery"]');

            if (tab.dataset.type === "cod")  {

                // hide pickup
                pickupTab.classList.add("d-none");

                // force delivery active
                deliveryTab.classList.add("active");
                pickupTab.classList.remove("active");

                deliveryTab.querySelector("input[type='radio']").checked = true;

                shippingRow.classList.remove("d-none");
                shippingZoneWrapper.classList.remove("d-none");
                zoneSelect.disabled = false;

                updateTotal("delivery");

            } else {

                pickupTab.classList.remove("d-none");
            }

            const currency = tab.dataset.currency;
            currencyInput.value = currency;
            showCurrency(currency);
            updateShippingZoneText(currency);
        }

        // ---- Summary values ----
        const subtotalUsd = parseFloat(document.getElementById("subtotalUsd").dataset.value);
        const subtotalMmk = parseFloat(document.getElementById("subtotalMmk").dataset.value);

        const taxUsd = (subtotalUsd * taxPercent) / 100;
        const taxMmk = roundTo50((subtotalMmk * taxPercent) / 100);

        // update tax display from DB percent
        document.getElementById("taxUsd").innerText = "$" + taxUsd.toFixed(2);
        document.getElementById("taxMmk").innerText = taxMmk.toLocaleString() + " Ks.";

        const shippingUsdEl = document.getElementById("shippingUsd");
        const shippingMmkEl = document.getElementById("shippingMmk");

        const totalUsdEl = document.getElementById("totalUsd");
        const totalMmkEl = document.getElementById("totalMmk");

        function updateTotal(deliveryType) {

            let shippingMMK = 0;
            let shippingUSD = 0;

            if (deliveryType === "delivery") {
                shippingMMK = currentShippingMMK;
                shippingUSD = shippingMMK / mmkRate;
            }

            shippingUsdEl.innerText = "$" + shippingUSD.toFixed(2);
            shippingMmkEl.innerText = shippingMMK.toLocaleString() + " Ks.";

            const totalUsd = subtotalUsd + taxUsd + shippingUSD;
            const totalMmk = roundTo50(subtotalMmk + taxMmk + shippingMMK);

            totalUsdEl.innerText = "$" + totalUsd.toFixed(2);
            totalMmkEl.innerText = totalMmk.toLocaleString() + " Ks.";
        }

        // ===============================
        // RESTORE STATE AFTER VALIDATION
        // ===============================

        // Restore delivery type
        const checkedDelivery = document.querySelector("input[name='delivery_type']:checked");
        let initialDeliveryType = "delivery";

        if (checkedDelivery) {
            initialDeliveryType = checkedDelivery.value;

            deliveryTabs.forEach(t => t.classList.remove("active"));

            const activeTab = document.querySelector(`.delivery-tab[data-type="${initialDeliveryType}"]`);
            if (activeTab) activeTab.classList.add("active");

            if (initialDeliveryType === "pickup") {
                shippingRow.classList.add("d-none");
                shippingZoneWrapper.classList.add("d-none");
                zoneSelect.disabled = true;
            } else {
                zoneSelect.disabled = false;
            }
        }

        // Restore shipping zone fee if selected
        if (zoneSelect.value) {
            const selectedOption = zoneSelect.options[zoneSelect.selectedIndex];
            currentShippingMMK = parseFloat(selectedOption.dataset.feeMmk || 0);
        }

        // Update totals correctly
        updateTotal(initialDeliveryType);

        deliveryTabs.forEach(tab => {
            tab.addEventListener("click", function () {

                deliveryTabs.forEach(t => t.classList.remove("active"));
                tab.classList.add("active");

                tab.querySelector("input[type='radio']").checked = true;

                const type = tab.dataset.type;

                if (type === "pickup") {
                    shippingRow.classList.add("d-none");
                    shippingZoneWrapper.classList.add("d-none");
                    zoneSelect.value = "";
                    zoneSelect.disabled = true;
                    zoneSelect.required = false;
                    currentShippingMMK = 0;
                } else {
                    shippingRow.classList.remove("d-none");
                    shippingZoneWrapper.classList.remove("d-none");
                    zoneSelect.disabled = false;
                    zoneSelect.required = true;
                }

                updateTotal(type);
            });
        });

        zoneSelect.addEventListener("change", function () {
            const selected = this.options[this.selectedIndex];
            currentShippingMMK = parseFloat(selected.dataset.feeMmk || 0);

            const activeDelivery = document.querySelector(".delivery-tab.active")
                ?.dataset.type || "delivery";

            updateTotal(activeDelivery);
        });

        // payment click
        paymentTabs.forEach(tab => {

            tab.addEventListener('click', function () {
                tab.querySelector("input[type='radio']").checked = true;
                switchPaymentTab(tab);
            });

            tab.querySelector("input[type='radio']").addEventListener("change", function () {
                switchPaymentTab(tab);
            });
        });

        // ===============================
        // RESTORE PAYMENT TAB AFTER VALIDATION
        // ===============================
        const checkedRadio = document.querySelector("input[name='payment_method']:checked");

        if (checkedRadio) {

            const checkedTab = checkedRadio.closest(".payment-tab");

            if (checkedTab) {

                // remove active from all
                paymentTabs.forEach(t => t.classList.remove('active'));

                // hide all forms
                document.querySelectorAll('.payment-form').forEach(f => {
                    f.classList.add('d-none');

                    f.querySelectorAll("input, select, textarea").forEach(input => {
                        input.disabled = true;
                    });
                });

                // activate correct tab
                checkedTab.classList.add('active');

                // show correct form
                const targetId = "form-" + checkedTab.dataset.target;
                const targetForm = document.getElementById(targetId);

                if (targetForm) {
                    targetForm.classList.remove('d-none');

                    // enable inputs in active form
                    targetForm.querySelectorAll("input, select, textarea").forEach(input => {
                        input.disabled = false;
                    });
                }

                // restore currency
                const currency = checkedTab.dataset.currency;
                currencyInput.value = currency;
                showCurrency(currency);

                // hide pickup if COD is active on load
                const pickupTab = document.querySelector('.delivery-tab[data-type="pickup"]');
                const deliveryTab = document.querySelector('.delivery-tab[data-type="delivery"]');

                if (checkedTab.dataset.type === "cod") {

                    pickupTab.classList.add("d-none");

                    // force delivery selected
                    deliveryTabs.forEach(t => t.classList.remove("active"));
                    deliveryTab.classList.add("active");

                    deliveryTab.querySelector("input[type='radio']").checked = true;

                    shippingRow.classList.remove("d-none");
                    shippingZoneWrapper.classList.remove("d-none");
                    zoneSelect.disabled = false;

                    updateTotal("delivery");

                } else {
                    pickupTab.classList.remove("d-none");
                }
            }
        }
        updateShippingZoneText(currencyInput.value);
    });

    document.querySelectorAll('.payment-screenshot-input').forEach(input => {

        input.addEventListener('change', function () {

            const file = this.files[0];
            const previewId = this.dataset.preview;
            const previewImg = document.getElementById(previewId);

            if (!previewImg) return;

            if (file) {

                const reader = new FileReader();

                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('d-none');
                };

                reader.readAsDataURL(file);

            } else {
                previewImg.classList.add('d-none');
                previewImg.src = "#";
            }
        });

    });
</script>

@endsection
