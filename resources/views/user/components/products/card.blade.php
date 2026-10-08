<div class="eco-product-card">

    <div class="eco-product-image">

        <div class="eco-badge-stack">

            @if($product->isNew())
                <span class="eco-new-badge">NEW</span>
            @endif

            @if($product->eco_badge)
                <span class="eco-badge">
                    {{ $product->display_eco_badge }}
                </span>
            @endif

        </div>

        <a href="{{ route('product.show',$product->id) }}" class="eco-product-link">
            <img src="{{ $product->primaryImage
            ? asset('storage/'.$product->primaryImage->image)
            : asset('default/no-image.png') }}"
            alt="{{ $product->display_name }}">
        </a>

        <button class="eco-wishlist-btn"
                data-product="{{ $product->id }}">
            <i class="{{ $product->isInWishlist ? 'fas' : 'far' }} fa-heart"></i>
        </button>

        <span class="eco-category">
            {{ $product->category->display_name ?? 'No Category' }}
        </span>
    </div>

    <div class="eco-product-body">
        <h5>{{ $product->display_name }}</h5>

        @php
            if ($product->available_stock == 0) {
                $stockClass = 'out-stock';
                $stockIcon = 'fa-times-circle';
                $stockText = 'Out of Stock';
            } elseif ($product->available_stock <= 5) {
                $stockClass = 'low-stock';
                $stockIcon = 'fa-exclamation-circle';
                $stockText = 'Low Stock';
            } else {
                $stockClass = 'in-stock';
                $stockIcon = 'fa-check-circle';
                $stockText = 'In Stock';
            }
        @endphp

        <div class="eco-stock-rating-row">
            <div class="eco-stock {{ $stockClass }}">
                <i class="fas {{ $stockIcon }}"></i>
                {{ $stockText }}
            </div>

            <div class="eco-rating-side">
                @php
                    $avgRating = $product->reviews_avg_rating ?? 0;
                    $reviewCount = $product->reviews_count ?? 0;

                    // round to nearest 0.5 (ex: 4.4 => 4.5, 4.2 => 4.0)
                    $roundedRating = round($avgRating * 2) / 2;

                    $fullStars = floor($roundedRating);
                    $halfStar = ($roundedRating - $fullStars) == 0.5;
                @endphp

                @if($avgRating > 0)
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $fullStars)
                            <i class="fas fa-star"></i>
                        @elseif($i == ($fullStars + 1) && $halfStar)
                            <i class="fas fa-star-half-alt"></i>
                        @else
                            <i class="far fa-star eco-star-muted"></i>
                        @endif
                    @endfor

                    <span class="eco-review-count">
                        ({{ $reviewCount }})
                    </span>
                @else
                    <span class="eco-no-rating">Not yet rated</span>
                @endif
            </div>
        </div>

        <div class="eco-prices">
            <span class="mmk">{{ number_format($product->price_mmk) }} Ks.</span>
            <span class="usd">$ {{ number_format($product->price_usd,2) }}</span>
        </div>
    </div>
</div>
