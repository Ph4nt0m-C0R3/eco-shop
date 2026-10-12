@extends('user.layouts.master')

@section('content')

<style>
    .order-wrapper{
        background:#f7f8fc;
        min-height:100vh;
        padding:50px 0;
        margin-top:120px;
    }

    .order-card{
        background:#fff;
        border-radius:16px;
        box-shadow:0 10px 35px rgba(0,0,0,0.06);
        padding:25px;
    }

    .order-title{
        font-weight:800;
        font-size:20px;
        margin-bottom:20px;
    }

    .info-box{
        border:1px solid #eceef5;
        border-radius:14px;
        padding:18px;
        background:#fff;
        margin-bottom:15px;
    }

    .info-label{
        font-weight:700;
        font-size:13px;
        color:#555;
        margin-bottom:5px;
    }

    .info-value{
        font-weight:700;
        font-size:14px;
        color:#111;
    }

    .badge-status{
        font-size:12px;
        font-weight:700;
        padding:6px 12px;
        border-radius:30px;
        display:inline-block;
    }

    .status-paid{ background:#d1fae5; color:#065f46; }
    .status-pending{ background:#fef3c7; color:#92400e; }
    .status-cancelled{ background:#fee2e2; color:#991b1b; }
    .status-failed { background:#fee2e2; color:#991b1b; }

    .product-row{
        display:flex;
        justify-content:space-between;
        padding:12px 0;
        border-bottom:1px solid #f0f1f6;
        font-size:14px;
    }

    .product-row:last-child{
        border-bottom:none;
    }

    .summary-row{
        display:flex;
        justify-content:space-between;
        font-size:14px;
        margin-bottom:10px;
    }

    .summary-total{
        font-weight:900;
        font-size:18px;
        color:#111;
    }

    .btn-back{
        border-radius:12px;
        padding:10px 16px;
        font-weight:700;
    }

    .eco-action-alert{
        border-radius:16px;
        padding:18px 20px;
        border:1px solid rgba(0,0,0,0.05);
        box-shadow:0 8px 20px rgba(0,0,0,0.04);
    }

    .eco-action-alert .eco-alert-title{
        font-weight:800;
        font-size:15px;
    }

    .eco-action-alert .eco-alert-text{
        font-size:13px;
        font-weight:600;
        color:#555;
        margin-top:4px;
    }

    .eco-modal-overlay{
        position:fixed;
        inset:0;
        background:rgba(0,0,0,0.45);
        display:flex;
        justify-content:center;
        align-items:center;
        z-index:9999;
        opacity:0;
        visibility:hidden;
        transition:0.25s ease;
        padding:15px;
    }

    .eco-modal-overlay.show{
        opacity:1;
        visibility:visible;
    }

    .eco-modal-box{
        background:#fff;
        width:100%;
        padding:20px;
        border-radius:16px;
        box-shadow:0 15px 40px rgba(0,0,0,0.15);
        transform:translateY(15px);
        transition:0.25s ease;
    }

    .eco-modal-overlay.show .eco-modal-box{
        transform:translateY(0);
    }

    /* ===================================
    MOBILE HEADER FIX (≤ 425px)
    =================================== */
    @media (max-width: 425px) {

        /* Stack back button + title */
        .order-card > .d-flex.justify-content-between{
            flex-direction: column;
            align-items: stretch !important;
            gap: 12px;
        }

        /* Make back button full width */
        .btn-back{
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Reduce title size + add spacing */
        .order-title{
            font-size: 16px;
            line-height: 1.4;
            text-align: left;
        }

    }
</style>

<div class="order-wrapper">
    <div class="container">

        <div class="order-card">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('user.orders') }}" class="btn btn-outline-secondary btn-back">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    {{ __('orders.back') }}
                </a>

                <h4 class="order-title mb-0">
                    <i class="fa-solid fa-receipt me-2 text-primary"></i>
                    {{ __('orders.order_details') }}
                </h4>
            </div>

            @php
                $orderStatusClass = match($order->status) {
                    'pending_payment' => 'status-pending',
                    'processing' => 'status-paid',
                    'shipped' => 'status-paid',
                    'delivered' => 'status-paid',
                    'cancelled' => 'status-cancelled',
                    default => 'status-pending',
                };

                $paymentStatusClass = match($order->payment_status) {
                    'paid' => 'status-paid',
                    'unpaid' => 'status-pending',
                    'failed' => 'status-failed',
                    default => 'status-pending',
                };
            @endphp

            <div class="row g-3 mb-3">

                <div class="col-md-6">
                    <div class="info-box">
                        <div class="info-label">{{ __('orders.order_number') }}</div>
                        <div class="info-value">#{{ $order->order_number }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-box">
                        <div class="info-label">{{ __('orders.order_date') }}</div>
                        <div class="info-value">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">{{ __('orders.payment_method') }}</div>
                            <div class="info-value">
                                {{ $order->paymentMethod?->display_name ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">{{ __('orders.delivery_type') }}</div>

                            @php
                                $deliveryLabel = $order->delivery_type === 'pickup'
                                    ? (app()->getLocale() === 'mm' ? 'ဆိုင်တွင်လာယူမည်' : 'Store Pickup')
                                    : (app()->getLocale() === 'mm' ? 'အိမ်အရောက်ပို့မည်' : 'Home Delivery');

                                $deliveryIcon = $order->delivery_type === 'pickup'
                                    ? 'fa-store'
                                    : 'fa-truck-fast';

                                $deliveryClass = $order->delivery_type === 'pickup'
                                    ? 'status-paid'
                                    : 'status-pending';
                            @endphp

                            <div class="info-value">
                                <span class="badge-status {{ $deliveryClass }}">
                                    <i class="fa-solid {{ $deliveryIcon }} me-1"></i>
                                    {{ $deliveryLabel }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-box">
                        <div class="info-label">{{ __('orders.order_status') }}</div>
                        <div class="info-value">
                            <span class="badge-status {{ $orderStatusClass }}">
                                {{ strtoupper($order->status) }}
                            </span>
                        </div>
                    </div>

                    @if($order->status === 'pending_payment')
                        <small class="text-muted">{{ __('orders.waiting_payment') }}</small>
                    @elseif($order->status === 'processing')
                        <small class="text-muted">{{ __('orders.processing_text') }}</small>
                    @elseif($order->status === 'shipped')
                        <small class="text-muted">{{ __('orders.on_the_way') }}</small>
                    @elseif($order->status === 'delivered')
                        <small class="text-success">{{ __('orders.delivered_success') }}</small>
                    @endif
                </div>

                <div class="col-md-6">
                    <div class="info-box">
                        <div class="info-label">{{ __('orders.payment_status') }}</div>
                        <div class="info-value">
                            <span class="badge-status {{ $paymentStatusClass }}">
                                {{ strtoupper($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                @if(
                    $order->paymentMethod?->type === 'stripe'
                    && $order->payment_status !== 'paid'
                    && $order->status === 'pending_payment'
                )

                    <div class="alert-warning eco-action-alert mt-3">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fa-solid fa-circle-exclamation mt-1"></i>

                            <div>
                                <div class="eco-alert-title">
                                    {{ __('orders.payment_required') }}
                                </div>

                                <div class="eco-alert-text">
                                    @if($order->payment_status === 'failed')
                                        {{ __('orders.payment_failed_text') }}
                                    @else
                                        {{ __('orders.payment_not_completed_text') }}
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('stripe.checkout', $order) }}"
                                    class="btn btn-warning"
                                    style="border-radius:12px;font-weight:700;">
                                        <i class="fa-solid fa-rotate-right me-2"></i>
                                        {{ __('orders.retry_payment') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @endif

                @if(
                    $order->paymentMethod?->type === 'cod'
                    && $order->payment_status === 'unpaid'
                    && $order->status === 'pending_payment'
                )

                    <div class="alert-danger eco-action-alert mt-3">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fa-solid fa-circle-xmark mt-1"></i>

                            <div>
                                <div class="eco-alert-title">
                                    {{ __('orders.cancel_order') }}
                                </div>

                                <div class="eco-alert-text">
                                    {{ __('orders.cancel_hint') }}
                                </div>

                                <div class="mt-3">
                                    <form id="cancelOrderForm"
                                    action="{{ route('user.orders.cancel', $order) }}"
                                    method="POST">

                                        @csrf

                                        <button type="button"
                                                onclick="openEcoModalById('cancelOrderOverlay')"
                                                class="btn btn-danger"
                                                style="border-radius:12px;font-weight:700;">
                                            <i class="fa-solid fa-xmark me-2"></i>
                                            {{ __('orders.cancel_order') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                @endif

                <div class="col-12">
                    <div class="info-box">
                        <div class="info-label">{{ __('orders.shipping_address') }}</div>
                        <div class="info-value">{{ $order->shipping_address }}</div>
                    </div>
                </div>

            </div>

            <div class="info-box">
                <h6 class="fw-bold mb-3">
                    <i class="fa-solid fa-cart-shopping me-2 text-secondary"></i>
                    {{ __('orders.order_items') }}
                </h6>

                @foreach($order->items as $item)
                    <div class="product-row">
                        <div>
                            <strong>{{ $item->product_name }}</strong>
                            <div style="font-size:12px;color:#666;">
                                {{ __('orders.qty') }} {{ $item->quantity }}
                            </div>
                        </div>

                        <div style="font-weight:800;">
                            {{ format_money($item->total_price, $order->currency_code) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="info-box">
                <h6 class="fw-bold mb-3">
                    <i class="fa-solid fa-file-invoice-dollar me-2 text-secondary"></i>
                    {{ __('orders.order_summary') }}
                </h6>

                <div class="summary-row">
                    <span>{{ __('orders.subtotal') }}</span>
                    <span>{{ format_money(
                $order->currency_code === 'USD'
                            ? $order->subtotal_usd
                            : $order->subtotal_mmk,
                        $order->currency_code
                    ) }}
                    </span>
                </div>

                @php
                    $taxPercent = \App\Models\TaxSetting::first()->percent ?? 0;
                    $formattedTax = rtrim(rtrim(number_format($taxPercent, 2), '0'), '.');
                @endphp

                <div class="summary-row">
                    <span>{{ __('orders.tax') }} ({{ rtrim(rtrim(number_format($taxPercent, 2), '0'), '.') }}%)</span>
                    <span>{{ format_money($order->tax, $order->currency_code) }}</span>
                </div>

                @if($order->shipping_fee > 0)
                    <div class="summary-row">
                        <span>{{ __('orders.shipping_fee') }}</span>
                        <span>{{ format_money($order->shipping_fee, $order->currency_code) }}</span>
                    </div>
                @endif

                <hr>

                <div class="summary-row summary-total">
                    <span>{{ __('orders.total') }}</span>
                    <span>{{ format_money($order->grand_total, $order->currency_code) }}</span>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- CANCEL ORDER CONFIRM MODAL -->
<div id="cancelOrderOverlay" class="eco-modal-overlay">

    <div class="eco-modal-box" style="max-width:450px; border-radius:18px;">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold mb-0">
                <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>
                {{ __('orders.cancel_order') }}
            </h5>

            <button type="button"
                    class="eco-modal-close"
                    onclick="closeEcoModalById('cancelOrderOverlay')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <p class="text-muted mb-3" style="font-size:14px;">
            {{ __('orders.cancel_confirm_text') }}
        </p>

        <div class="d-flex justify-content-end gap-2">

            <button type="button"
                    class="btn btn-outline-secondary"
                    style="border-radius:12px;font-weight:700;"
                    onclick="closeEcoModalById('cancelOrderOverlay')">
                {{ __('orders.keep_order') }}
            </button>

            <button type="button"
                    class="btn btn-danger"
                    style="border-radius:12px;font-weight:700;"
                    onclick="document.getElementById('cancelOrderForm').submit();">
                {{ __('orders.confirm_cancel') }}
            </button>

        </div>

    </div>
</div>

@endsection
