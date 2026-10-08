<div class="table-responsive">
    <table class="table table-hover align-middle text-center">
        <thead>
            <tr>
                <th>Order No</th>
                <th>User</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Date</th>
                <th width="120">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="fw-semibold">#{{ $order->order_number }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>{{ format_money($order->grand_total, $order->currency_code) }}</td>

                    @php
                        $orderStatusClass = match($order->status) {
                            'pending_payment' => 'eco-status-pending_payment',
                            'processing' => 'eco-status-processing',
                            'shipped' => 'eco-status-shipped',
                            'cancelled' => 'eco-status-cancelled',
                            default => 'eco-status-pending_payment',
                        };

                        $orderStatusText = match($order->status) {
                            'pending_payment' => 'Pending Payment',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'cancelled' => 'Cancelled',
                            default => ucfirst($order->status),
                        };
                    @endphp

                    <td>
                        <span class="eco-order-badge {{ $orderStatusClass }}">
                            {{ $orderStatusText }}
                        </span>
                    </td>

                    @php
                        $paymentStatusClass = match($order->payment_status) {
                            'paid' => 'eco-payment-paid',
                            'unpaid' => 'eco-payment-unpaid',
                            'failed' => 'eco-payment-failed',
                            default => 'eco-payment-unpaid',
                        };

                        $paymentStatusText = match($order->payment_status) {
                            'paid' => 'Paid',
                            'unpaid' => 'Unpaid',
                            'failed' => 'Failed',
                            default => ucfirst($order->payment_status),
                        };
                    @endphp

                    <td>
                        <span class="eco-order-badge {{ $paymentStatusClass }}">
                            {{ $paymentStatusText }}
                        </span>
                    </td>

                    <td>{{ $order->created_at->format('d M Y') }}</td>

                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}"
                        class="btn btn-sm btn-outline-success rounded-pill">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

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

        {{-- Always first --}}
        <a href="{{ $orders->url(1) }}"
        class="eco-page-number no-spinner {{ $current == 1 ? 'active' : '' }}">
            1
        </a>

        {{-- Left dots --}}
        @if ($start > 2)
            <span class="px-2">...</span>
        @endif

        {{-- Middle --}}
        @for ($page = max(2, $start); $page <= min($end, $last - 1); $page++)
            <a href="{{ $orders->url($page) }}"
            class="eco-page-number no-spinner {{ $page == $current ? 'active' : '' }}">
                {{ $page }}
            </a>
        @endfor

        {{-- Right dots --}}
        @if ($end < $last - 1)
            <span class="px-2">...</span>
        @endif

        {{-- Always last --}}
        @if ($last > 1)
            <a href="{{ $orders->url($last) }}"
            class="eco-page-number no-spinner {{ $current == $last ? 'active' : '' }}">
                {{ $last }}
            </a>
        @endif

    </nav>
</div>
@endif
