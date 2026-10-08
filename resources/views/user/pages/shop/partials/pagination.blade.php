@if ($products->count() > 0 && $products->hasPages())
<div class="eco-pagination-wrapper mt-5">
    <nav class="eco-pagination">

        @php
            $current = $products->currentPage();
            $last = $products->lastPage();

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
        <a href="{{ $products->url(1) }}"
           class="eco-page-number no-spinner {{ $current == 1 ? 'active' : '' }}">
            1
        </a>

        {{-- Left dots --}}
        @if ($start > 2)
            <span class="eco-page-dots">...</span>
        @endif

        {{-- Middle numbers --}}
        @for ($page = max(2, $start); $page <= min($end, $last - 1); $page++)
            <a href="{{ $products->url($page) }}"
               class="eco-page-number no-spinner {{ $page == $current ? 'active' : '' }}">
                {{ $page }}
            </a>
        @endfor

        {{-- Right dots --}}
        @if ($end < $last - 1)
            <span class="eco-page-dots">...</span>
        @endif

        {{-- Always show last (if more than 1 page) --}}
        @if ($last > 1)
            <a href="{{ $products->url($last) }}"
               class="eco-page-number no-spinner {{ $current == $last ? 'active' : '' }}">
                {{ $last }}
            </a>
        @endif

    </nav>
</div>
@endif
