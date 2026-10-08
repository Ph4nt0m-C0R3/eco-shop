<style>
    /* Fix wishlist button position inside product header */
    .luxury-product-header .eco-wishlist-btn {
        position: static !important;
        width: 44px;
        height: 44px;
    }

    @media (max-width: 576px) {
        .luxury-product-header .eco-wishlist-btn {
            width: 38px;
            height: 38px;
            font-size: 14px;
        }
    }

    @media (max-width: 320px) {
        .luxury-product-header .eco-wishlist-btn {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }
    }
</style>

<!-- Product Header -->
<div class="luxury-product-header mb-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="luxury-product-title">{{ $product->display_name }}</h1>
            <div class="luxury-product-subtitle">
                <span class="luxury-category">{{ $product->category->display_name ?? '' }}</span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="luxury-share-btn" title="Share">
                <i class="fas fa-share-alt"></i>
            </button>

            <button
                class="eco-wishlist-btn"
                data-product="{{ $product->id }}"
                title="Add to Wishlist"
            >
                <i class="{{ isset($product->isInWishlist) && $product->isInWishlist ? 'fas' : 'far' }} fa-heart"></i>
            </button>
        </div>
    </div>

    <!-- Rating -->
    <div class="luxury-rating-meta mb-4">
        <div class="d-flex align-items-center gap-4">
            <div class="luxury-rating-badge">
                <div class="luxury-stars">

                    @php
                        // round to nearest 0.5 (4.4 => 4.5, 4.2 => 4.0)
                        $roundedRating = round($averageRating * 2) / 2;

                        $fullStars = floor($roundedRating);
                        $halfStar = ($roundedRating - $fullStars) == 0.5;
                    @endphp

                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $fullStars)
                            <i class="fas fa-star"></i>
                        @elseif($i == ($fullStars + 1) && $halfStar)
                            <i class="fas fa-star-half-alt"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor

                </div>

                <span class="luxury-rating-text">
                    {{ $averageRating }} / 5
                </span>
            </div>

            <div class="luxury-review-count">
                <span>
                    ({{ $totalReviews }})
                    {{ app()->getLocale() === 'mm' ? 'သုံးသပ်ချက်' : 'reviews' }}
                </span>
            </div>
        </div>
    </div>
</div>
