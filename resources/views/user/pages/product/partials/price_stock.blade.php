<!-- Price & Stock Section (Combined) -->
<div class="luxury-price-section mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="luxury-price-main">{{ number_format($product->price_mmk) }} <span class="luxury-currency"> Ks.</span></h2>
            <div class="luxury-price-usd">$ {{ number_format($product->price_usd, 2) }}</div>
        </div>

        <!-- Stock Status - Now in same div as price -->
        @php
            if ($product->available_stock == 0) {
                $stockIcon = 'fa-times-circle';
                $stockText = app()->getLocale() === 'mm' ? 'ပစ္စည်းကုန်ပါပြီ' : 'Out of Stock';
                $stockClass = 'out-of-stock';
            } elseif ($product->available_stock <= 5) {
                $stockIcon = 'fa-exclamation-circle';
                $stockText = app()->getLocale() === 'mm' ? 'ပစ္စည်းနည်းနေပါပြီ' : 'Low Stock';
                $stockClass = 'low-stock';
            } else {
                $stockIcon = 'fa-check-circle';
                $stockText = app()->getLocale() === 'mm' ? 'ပစ္စည်းရှိသည်' : 'In Stock';
                $stockClass = 'in-stock';
            }
        @endphp

        <div class="luxury-stock-simple {{ $stockClass }}">
            <i class="fas {{ $stockIcon }} me-2"></i>
            <span class="luxury-stock-status">{{ $stockText }}</span>
            @if($product->available_stock > 0)
                <span class="luxury-stock-count">({{ $product->available_stock }} {{ app()->getLocale() === 'mm' ? 'ခုကျန်' : 'available' }})</span>
            @endif
        </div>
    </div>
</div>
