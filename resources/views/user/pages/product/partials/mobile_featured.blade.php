<div class="luxury-featured-products-mobile">
    <h4 class="luxury-section-title mb-4">
        <i class="fas fa-star me-2"></i>
        {{ app()->getLocale() === 'mm' ? 'အထူးထုတ်ကုန်များ' : 'Featured Products' }}
    </h4>
    <div class="luxury-featured-list">
        @foreach($featuredProducts as $rp)
        <a href="{{ route('product.show', $rp->id) }}" class="luxury-featured-item">
            <div class="luxury-featured-image">
                <img src="{{ $rp->primaryImage ? asset('storage/'.$rp->primaryImage->image) : asset('default/no-image.png') }}"
                     alt="{{ $rp->display_name }}">
            </div>
            <div class="luxury-featured-info">
                <h6>{{ Str::limit($rp->display_name, 25) }}</h6>
                <div class="luxury-featured-price">
                    <span class="luxury-featured-price-main">{{ number_format($rp->price_mmk) }} Ks.</span>
                </div>
                <div class="luxury-featured-rating">
                    @php
                        $avgRating = $rp->reviews_avg_rating ?? 0;

                        // round to nearest 0.5 (4.4 => 4.5, 4.2 => 4.0)
                        $roundedRating = round($avgRating * 2) / 2;

                        $fullStars = floor($roundedRating);
                        $halfStar = ($roundedRating - $fullStars) == 0.5;
                    @endphp

                    @if($avgRating > 0)
                        <div class="luxury-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $fullStars)
                                    <i class="fas fa-star"></i>
                                @elseif($i == ($fullStars + 1) && $halfStar)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                            <span class="ms-1">({{ number_format($avgRating, 1) }})</span>
                        </div>
                    @else
                        <span class="text-muted">
                            {{ app()->getLocale()==='mm' ? 'အဆင့်သတ်မှတ်ချက်မရှိသေးပါ' : 'Not yet rated' }}
                        </span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
