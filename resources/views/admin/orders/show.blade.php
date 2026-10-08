@extends('admin.layouts.master')

@section('main_content')

<style>
    .eco-section {
        background:#fff;
        border-radius:1rem;
        padding:1.5rem;
        box-shadow:0 5px 15px rgba(0,0,0,0.05);
    }

    .eco-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .eco-summary-row span:first-child {
        font-weight: 600;
        color: #555;
    }

    .eco-summary-row span:last-child {
        font-weight: 600;
        text-align: right;
    }

    .eco-summary-total {
        font-size: 18px;
        font-weight: 700;
    }

    .eco-order-badge {
        padding: 0.4rem 0.9rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* ORDER STATUS */
    .eco-status-pending_payment { background:#fff3cd; color:#856404; }
    .eco-status-processing { background:#e3f2fd; color:#1565c0; }
    .eco-status-shipped { background:#ede7f6; color:#512da8; }
    .eco-status-cancelled { background:#ffebee; color:#c62828; }

    /* PAYMENT STATUS */
    .eco-payment-paid { background:#e8f5e9; color:#2e7d32; }
    .eco-payment-unpaid { background:#fff3cd; color:#856404; }
    .eco-payment-failed { background:#ffebee; color:#c62828; }

    /* PAYMENT METHOD */
    .eco-method-cod { background:#e3f2fd; color:#1565c0; }
    .eco-method-stripe { background:#ede7f6; color:#512da8; }
    .eco-method-e_wallet { background:#e8f5e9; color:#2e7d32; }

    /* DELIVERY TYPE */
    .eco-delivery-delivery { background:#fff3cd; color:#856404; }
    .eco-delivery-pickup { background:#e0f2f1; color:#00695c; }

    .alert-secondary.no-action {
        padding: 15px 5px;
        border-radius: 10px;
    }
</style>

<div class="container-fluid my-4">

    <!-- Header -->
    <div class="eco-header-common eco-header-sm mb-4">

        <div class="eco-header-left">

            <!-- Back Button -->
            <a href="{{ route('admin.orders') }}"
            class="btn btn-light text-success rounded-circle">
                <i class="fas fa-arrow-left"></i>
            </a>

            <!-- Title -->
            <div>
                <h5 class="eco-header-title mb-0">
                    <i class="fa-solid fa-receipt mr-1"></i>
                    Order #{{ $order->order_number }}
                </h5>
                <p class="eco-header-subtitle">
                    Order details & management
                </p>
            </div>

        </div>

    </div>

    @include('components.partials.success-alert')
    @include('components.partials.error-alert')

    <div class="row g-4">

        <!-- LEFT SIDE -->
        <div class="col-lg-8">

            <!-- Order Items -->
            <div class="eco-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Order Items</h5>
                    <span class="badge bg-light text-dark">
                        {{ $order->items->count() }} items
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th width="120">Price</th>
                                <th width="80">Qty</th>
                                <th width="120">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="text-start">{{ $item->product_name }}</td>
                                    <td>
                                        {{ format_money($item->total_price / $item->quantity, $order->currency_code) }}
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="fw-bold">
                                        {{ format_money($item->total_price, $order->currency_code) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="eco-section mt-4">
                <h5 class="mb-3">Shipping Information</h5>

                <div class="row">
                    <div class="col-md-4">
                        <p class="mb-1 text-muted">Phone</p>
                        <p class="fw-bold">{{ $order->phone }}</p>
                    </div>
                    <div class="col-md-8">
                        <p class="mb-1 text-muted">Full Address</p>
                        <div class="border rounded bg-light p-3">
                            {{ $order->shipping_address }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Screenshot -->
            @if($order->payment_screenshot)
                <div class="eco-section mt-4">
                    <h5 class="mb-3">Payment Screenshot</h5>
                    <img src="{{ asset('storage/'.$order->payment_screenshot) }}"
                         class="img-fluid rounded border"
                         style="max-width:300px;">
                </div>
            @endif

        </div>

        <!-- RIGHT SIDE -->
        <div class="col-lg-4">

            <!-- Order Summary -->
            <div class="eco-section">
                <h5 class="mb-3">Order Summary</h5>

                <div class="eco-summary-row">
                    <span>Status</span>
                    @php
                        $orderStatusClass = match($order->status) {
                            'pending_payment' => 'eco-status-pending_payment',
                            'processing' => 'eco-status-processing',
                            'shipped' => 'eco-status-shipped',
                            'cancelled' => 'eco-status-cancelled',
                            default => 'eco-status-pending_payment',
                        };
                    @endphp

                    <span class="eco-order-badge {{ $orderStatusClass }}">
                        {{ strtoupper($order->status) }}
                    </span>
                </div>

                <div class="eco-summary-row">
                    <span>Payment</span>
                    @php
                        $paymentStatusClass = match($order->payment_status) {
                            'paid' => 'eco-payment-paid',
                            'unpaid' => 'eco-payment-unpaid',
                            'failed' => 'eco-payment-failed',
                            default => 'eco-payment-unpaid',
                        };
                    @endphp

                    <span class="eco-order-badge {{ $paymentStatusClass }}">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                </div>

                <div class="eco-summary-row">
                    <span>Payment Method</span>

                    @php
                        $pm = $order->paymentMethod;

                        $label = $pm?->name_en ?? ucfirst($order->payment_method);
                        $icon  = $pm?->icon;
                    @endphp

                    <span class="eco-order-badge eco-method-{{ $order->payment_method }}">
                        @if($icon)
                            <img src="{{ asset('storage/'.$icon) }}"
                                alt="{{ $label }}"
                                style="height:16px;margin-right:6px;">
                        @endif
                        {{ $label }}
                    </span>
                </div>

                <div class="eco-summary-row">
                    <span>Delivery Type</span>
                    @php
                        $deliveryLabel = $order->delivery_type === 'pickup'
                            ? 'Store Pickup'
                            : 'Home Delivery';

                        $deliveryIcon = $order->delivery_type === 'pickup'
                            ? 'fa-store'
                            : 'fa-truck-fast';

                        $deliveryClass = 'eco-delivery-'.$order->delivery_type;
                    @endphp

                    <span class="eco-order-badge {{ $deliveryClass }}">
                        <i class="fa-solid {{ $deliveryIcon }} me-1"></i>
                        {{ $deliveryLabel }}
                    </span>
                </div>

                <hr>

                <div class="eco-summary-row">
                    <span>Subtotal</span>
                    <span>
                        {{ format_money(
                            $order->subtotal_usd * \App\Models\Currency::getRate($order->currency_code),
                            $order->currency_code
                        ) }}
                    </span>
                </div>

                @php
                    $taxPercent = \App\Models\TaxSetting::first()->percent ?? 0;
                    $formattedTax = rtrim(rtrim(number_format($taxPercent, 2), '0'), '.');
                @endphp

                <div class="eco-summary-row">
                    <span>Tax ({{ $formattedTax }}%)</span>
                    <span>{{ format_money($order->tax, $order->currency_code) }}</span>
                </div>

                @if($order->shipping_fee > 0)
                    <div class="eco-summary-row">
                        <span>Shipping Fee</span>
                        <span>{{ format_money($order->shipping_fee, $order->currency_code) }}</span>
                    </div>
                @endif

                <hr>

                <div class="eco-summary-row eco-summary-total">
                    <span>Total</span>
                    <span>{{ format_money($order->grand_total, $order->currency_code) }}</span>
                </div>
            </div>

            <!-- Customer -->
            <div class="eco-section mt-4">
                <h5 class="mb-3">Customer</h5>
                <p class="mb-1"><strong>Name:</strong> {{ $order->user->name ?? '' }}</p>
                <p class="mb-0"><strong>Email:</strong> {{ $order->user->email ?? '' }}</p>
            </div>

            <!-- Admin Actions -->
            <div class="eco-section mt-4">
                <h5 class="mb-3">Admin Actions</h5>

                @if($order->status === 'cancelled')
                    <div class="alert-secondary no-action text-center mb-0">
                        <i class="fa-solid fa-ban me-2"></i>
                        Order cancelled — no actions available.
                    </div>
                @else

                    @if($order->payment_status === 'unpaid' && strtolower($order->payment_method) !== 'stripe')
                        <form id="approvePaymentForm" method="POST" action="{{ route('admin.orders.approvePayment',$order) }}">
                            @csrf
                            <button type="button"
                                    class="btn btn-success w-100 mb-2"
                                    onclick="openEcoConfirmModal(
                                        'Approve Payment',
                                        'Approve this payment?',
                                        'This will mark the order as PAID.',
                                        'approvePaymentForm',
                                        'success'
                                    )">
                                Approve Payment
                            </button>
                        </form>

                        <form id="rejectPaymentForm" method="POST" action="{{ route('admin.orders.rejectPayment',$order) }}">
                            @csrf
                            <button type="button"
                                    class="btn btn-danger w-100 mb-2"
                                    onclick="openEcoConfirmModal(
                                        'Reject Payment',
                                        'Reject this payment?',
                                        'This action cannot be undone.',
                                        'rejectPaymentForm',
                                        'danger'
                                    )">
                                Reject Payment
                            </button>
                        </form>
                    @endif

                    @if($order->status === 'processing' && $order->payment_status === 'paid')
                        <form id="shipOrderForm" method="POST" action="{{ route('admin.orders.ship',$order) }}">
                            @csrf
                            <button type="button"
                                    class="btn btn-info w-100"
                                    onclick="openEcoConfirmModal(
                                        'Mark as Shipped',
                                        'Ship this order?',
                                        'Order status will be updated.',
                                        'shipOrderForm',
                                        'info'
                                    )">
                                Mark as Shipped
                            </button>
                        </form>
                    @endif

                @endif
            </div>

        </div>
    </div>

</div>

<!-- ===== Eco Confirm Modal ===== -->
<div class="eco-modal-overlay" id="ecoConfirmModal">
    <div class="eco-modal">

        <!-- Header -->
        <div class="eco-modal-header">
            <h5 id="ecoConfirmTitle" class="text-danger">
                Confirm Action
            </h5>

            <button class="eco-modal-close" onclick="closeEcoModalById('ecoConfirmModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="eco-modal-body text-center">
            <i id="ecoConfirmIcon" class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>

            <p class="mb-2" id="ecoConfirmMessage">
                Are you sure?
            </p>

            <small class="text-muted d-block mt-2" id="ecoConfirmWarning">
                This action cannot be undone!
            </small>
        </div>

        <!-- Footer -->
        <div class="eco-modal-footer justify-content-center">
            <button type="button"
                    class="btn btn-light rounded-pill px-4"
                    onclick="closeEcoModalById('ecoConfirmModal')">
                Cancel
            </button>

            <button type="button"
                    id="ecoConfirmSubmitBtn"
                    class="btn btn-danger rounded-pill px-4">
                Confirm
            </button>
        </div>

    </div>
</div>

<script>
    let ecoConfirmFormId = null;

    function openEcoConfirmModal(title, message, warning, formId, type = "danger") {

        ecoConfirmFormId = formId;

        document.getElementById("ecoConfirmTitle").innerText = title;
        document.getElementById("ecoConfirmMessage").innerText = message;
        document.getElementById("ecoConfirmWarning").innerText = warning;

        const icon = document.getElementById("ecoConfirmIcon");
        const submitBtn = document.getElementById("ecoConfirmSubmitBtn");
        const titleEl = document.getElementById("ecoConfirmTitle");

        // reset classes
        icon.className = "fas fa-exclamation-triangle fa-3x mb-3";
        submitBtn.className = "btn rounded-pill px-4";
        titleEl.className = "";

        if (type === "success") {
            icon.classList.add("text-success");
            submitBtn.classList.add("btn-success");
            titleEl.classList.add("text-success");
        } else if (type === "info") {
            icon.classList.add("text-info");
            submitBtn.classList.add("btn-info");
            titleEl.classList.add("text-info");
        } else {
            icon.classList.add("text-danger");
            submitBtn.classList.add("btn-danger");
            titleEl.classList.add("text-danger");
        }

        openEcoModalById("ecoConfirmModal");

        submitBtn.onclick = function () {
            if (ecoConfirmFormId) {
                closeEcoModalById("ecoConfirmModal");
                document.getElementById(ecoConfirmFormId).submit();
            }
        };
    }
</script>

@endsection
