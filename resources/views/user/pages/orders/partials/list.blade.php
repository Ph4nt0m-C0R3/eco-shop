@if($orders->count() == 0)

    <div class="eco-empty-state">
        <div class="eco-empty-box">

            <div class="eco-empty-icon">
                <i class="fas fa-leaf"></i>
            </div>

            <h3>{{ __('orders.no_orders_found') }}</h3>

            <p>{{ __('orders.no_orders_text') }}</p>

            <a href="{{ route('shop.index') }}" class="eco-empty-action-btn">
                <i class="fa-solid fa-bag-shopping me-2"></i>
                {{ __('orders.create_order') }}
            </a>

        </div>
    </div>

@else

    @foreach($orders as $order)

        @php
            $orderStatusClass = match($order->status) {
                'pending_payment' => 'status-pending',
                'processing' => 'status-confirmed',
                'shipped' => 'status-shipped',
                'delivered' => 'status-completed',
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

        <div class="order-row d-flex justify-content-between align-items-start align-items-md-center">

            <div class="w-50">
                <div class="order-number">
                    {{ __('orders.order_number_short') }} #{{ $order->order_number }}
                </div>

                <div class="order-meta">
                    <i class="fa-solid fa-calendar-days me-1"></i>
                    {{ $order->created_at->format('d M Y, h:i A') }}
                </div>

                <div class="order-meta">
                    <i class="fa-solid fa-credit-card me-1"></i>
                    {{ $order->paymentMethod?->display_name ?? '-' }}
                </div>

                <div class="mt-2">

                    <span class="badge-status {{ $orderStatusClass }}">
                        {{ str_replace('_',' ', strtoupper($order->status)) }}
                    </span>

                    <span class="badge-status {{ $paymentStatusClass }}">
                        {{ strtoupper(str_replace('_',' ',$order->payment_status)) }}
                    </span>

                </div>
            </div>

            <div class="w-50 text-end mt-3 mt-md-0 text-md-end">

                <div class="order-total mb-2">
                    {{ __('orders.total') }} {{ format_money($order->grand_total, $order->currency_code) }}
                </div>

                <div class="total-actions">

                    @if(
                        $order->payment_method === 'stripe'
                        && $order->payment_status !== 'paid'
                        && $order->status === 'pending_payment'
                    )
                        <a href="{{ route('stripe.checkout', $order) }}"
                        class="btn btn-warning order-2 order-md-1"
                        style="border-radius:12px;font-weight:700;">
                            <i class="fa-solid fa-credit-card me-2"></i>
                            {{ $order->payment_status === 'failed'
                                ? __('orders.retry_payment')
                                : __('orders.complete_payment')
                            }}
                        </a>
                    @endif

                    @if(
                        $order->payment_method === 'cod'
                        && $order->payment_status === 'unpaid'
                        && $order->status === 'pending_payment'
                    )
                        <form action="{{ route('user.orders.cancel', $order) }}"
                            method="POST"
                            class="order-2 order-md-1 cancelOrderForm">
                            @csrf

                            <button type="button"
                                    class="btn btn-danger btn-cancel w-100 w-md-auto"
                                    onclick="openCancelModal(this)">
                                <i class="fa-solid fa-xmark me-2"></i>
                                {{ __('orders.cancel_order') }}
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('user.orders.show', $order->id) }}"
                    class="btn-view order-1 order-md-2 ">
                        {{ __('orders.view_details') }}
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>

                </div>

            </div>

        </div>

    @endforeach

    @if ($orders->count() > 0 && $orders->hasPages())
    <div class="eco-pagination-wrapper mt-4">
        <nav class="eco-pagination">

            @php
                $current = $orders->currentPage();
                $last = $orders->lastPage();

                if ($current <= 3) {
                    $start = 1;
                    $end = min(4, $last);
                } elseif ($current >= $last - 2) {
                    $start = max($last - 3, 1);
                    $end = $last;
                } else {
                    $start = $current - 1;
                    $end = $current + 1;
                }
            @endphp

            {{-- Always show first --}}
            <a href="{{ $orders->url(1) }}"
            class="eco-page-number no-spinner {{ $current == 1 ? 'active' : '' }}">
                1
            </a>

            {{-- Left dots --}}
            @if ($start > 2)
                <span class="eco-page-dots">...</span>
            @endif

            {{-- Middle numbers --}}
            @for ($page = max(2, $start); $page <= min($end, $last - 1); $page++)
                <a href="{{ $orders->url($page) }}"
                class="eco-page-number no-spinner {{ $page == $current ? 'active' : '' }}">
                    {{ $page }}
                </a>
            @endfor

            {{-- Right dots --}}
            @if ($end < $last - 1)
                <span class="eco-page-dots">...</span>
            @endif

            {{-- Always show last --}}
            @if ($last > 1)
                <a href="{{ $orders->url($last) }}"
                class="eco-page-number no-spinner {{ $current == $last ? 'active' : '' }}">
                    {{ $last }}
                </a>
            @endif

        </nav>
    </div>
    @endif

@endif
