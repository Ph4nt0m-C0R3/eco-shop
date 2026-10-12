@if ($products->count() > 0 && $products->hasPages())
<div class="eco-pagination-wrapper mt-4">
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

        {{-- Always first --}}
        <a href="{{ $products->appends(request()->query())->url(1) }}"
           class="eco-page-number no-spinner {{ $current == 1 ? 'active' : '' }}">
            1
        </a>

        {{-- Left dots --}}
        @if ($start > 2)
            <span class="px-2">...</span>
        @endif

        {{-- Middle --}}
        @for ($page = max(2, $start); $page <= min($end, $last - 1); $page++)
            <a href="{{ $products->appends(request()->query())->url($page) }}"
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
            <a href="{{ $products->appends(request()->query())->url($last) }}"
               class="eco-page-number no-spinner {{ $current == $last ? 'active' : '' }}">
                {{ $last }}
            </a>
        @endif

    </nav>
</div>
@endif
