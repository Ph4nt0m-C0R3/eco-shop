<!-- Tabs Section with Reviews - Third on Mobile -->
<div class="luxury-tabs-section">
    <ul class="luxury-tabs-nav">
        <li class="luxury-tab-item active" data-tab="description">
            <i class="fas fa-file-alt me-2"></i>
            {{ app()->getLocale() === 'mm' ? 'အသေးစိတ်ဖော်ပြချက်' : 'Description' }}
        </li>
        <li class="luxury-tab-item" data-tab="reviews">
            <i class="fas fa-star me-2"></i>
            {{ app()->getLocale() === 'mm' ? 'သုံးသပ်ချက်များ' : 'Reviews' }}
            <span class="luxury-tab-badge" id="reviewCountBadge">
                {{ $totalReviews }}
            </span>
        </li>
    </ul>

    <div class="luxury-tabs-content">
        <!-- Description Tab -->
        <div class="luxury-tab-pane active" id="description-tab">
            <div class="luxury-description-content">
                <h4 class="luxury-tab-title mb-4">{{ app()->getLocale() === 'mm' ? 'ထုတ်ကုန်အကြောင်း' : 'About This Product' }}</h4>
                <div class="luxury-description-text">
                    <p>{{ $product->display_description ?? 'No detailed description available for this product.' }}</p>
                </div>
            </div>
        </div>

        <!-- Reviews Tab -->
        <div class="luxury-tab-pane" id="reviews-tab">
            <div class="luxury-reviews-content">
                <!-- Reviews Header -->
                <div class="luxury-reviews-header mb-5">
                    <h4 class="luxury-tab-title mb-3">{{ app()->getLocale() === 'mm' ? 'သုံးသပ်ချက်များ' : 'Customer Reviews' }}</h4>
                    <div class="row">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="luxury-overall-rating">
                                <div class="luxury-rating-score-large" id="avgRating">{{ number_format($averageRating, 1) }}</div>

                                <div class="luxury-stars-large" id="avgStars">
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

                                <div class="luxury-review-count-total" id="reviewTotal">{{ $totalReviews }} {{ app()->getLocale() === 'mm' ? 'သုံးသပ်ချက်' : 'reviews' }}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="luxury-rating-breakdown">

                            @for($star=5;$star>=1;$star--)
                                @php
                                    $count = $ratingBreakdown[$star] ?? 0;
                                    $percent = $totalReviews > 0
                                        ? ($count / $totalReviews) * 100
                                        : 0;
                                @endphp

                                <div class="luxury-rating-bar">
                                    <span class="luxury-rating-label">
                                        {{ $star }} <i class="fas fa-star"></i>
                                    </span>

                                    <div class="luxury-rating-progress">
                                        <div class="luxury-rating-fill"
                                            style="width: {{ $percent }}%">
                                        </div>
                                    </div>

                                    <span class="luxury-rating-count">
                                        {{ $count }}
                                    </span>
                                </div>

                            @endfor
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Write Review Button -->
                <div class="luxury-write-review mb-5">
                    @auth
                        <button class="luxury-review-btn">
                            <i class="fas fa-pen me-2"></i>
                            {{ app()->getLocale() === 'mm' ? 'သုံးသပ်ချက်ရေးသားရန်' : 'Write a Review' }}
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="luxury-review-btn">
                            <i class="fas fa-lock me-2"></i>
                            {{ app()->getLocale() === 'mm' ? 'သုံးသပ်ချက်ရေးရန် Login လုပ်ပါ' : 'Login to Write Review' }}
                        </a>
                    @endauth
                </div>

                <!-- Reviews List -->
                <div class="luxury-reviews-list"  id="reviewsList">

                    @forelse($reviews as $review)
                        <div class="luxury-review-item" data-review-id="{{ $review->id }}" data-user-id="{{ $review->user_id }}">
                            <div class="luxury-review-header">
                                <div class="luxury-reviewer-info">
                                    <div class="luxury-reviewer-avatar">
                                        <img src="{{ $review->user->avatar_url ?? asset('user/img/avatar.jpg') }}"
                                            class="luxury-avatar-img">
                                    </div>

                                    <div>
                                        <h6 class="luxury-reviewer-name">
                                            {{ auth()->id() === $review->user_id ? 'You' : ($review->user->name ?? 'User') }}
                                        </h6>
                                        <div class="luxury-review-date">
                                            {{ $review->updated_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>

                                <div class="luxury-review-rating">
                                    <div class="luxury-stars">
                                        @for($i=1;$i<=5;$i++)
                                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <div class="luxury-review-content">
                                <p class="luxury-review-text">{{ $review->message }}</p>
                            </div>

                            <button class="luxury-helpful-btn {{ $review->is_helpful_by_user ? 'active' : '' }}"
                                data-review-id="{{ $review->id }}"
                                data-active="{{ $review->is_helpful_by_user ? '1' : '0' }}">
                                <i class='fas fa-thumbs-up me-2'></i>Helpful (<span>{{ $review->helpful_count }}</span>)
                            </button>
                        </div>
                    @empty
                        <p class="text-center text-muted" id="noReviewsMessage">
                            {{ app()->getLocale() === 'mm' ? 'သုံးသပ်ချက် မရှိသေးပါ။' : 'No reviews yet.' }}
                        </p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</div>

@auth
<!-- =================== Review Modal ====================== -->
<div class="luxury-review-modal" id="reviewModal">
  <div class="luxury-review-overlay"></div>

  <div class="luxury-review-box">
    <!-- Close button -->
    <button type="button" class="luxury-review-close" aria-label="Close">
      <i class="fas fa-times"></i>
    </button>

    <!-- Modal header with icon -->
    <div class="luxury-review-header review">
      <div class="luxury-review-icon">
        <i class="fas fa-pen-fancy"></i>
      </div>
      <h4 class="luxury-review-title">
        {{ app()->getLocale() === 'mm' ? 'သုံးသပ်ချက်ရေးသားရန်' : 'Write Your Review' }}
      </h4>
    </div>

    <form id="reviewForm">
      @csrf
      <input type="hidden" name="product_id" value="{{ $product->id }}">

      <!-- STAR RATING -->
      <div class="luxury-rating-field">
        <label class="luxury-field-label">
          {{ app()->getLocale() === 'mm' ? 'အဆင့်သတ်မှတ်ချက်' : 'Your Rating' }}
          <span class="luxury-required">*</span>
        </label>

        <div class="luxury-star-select-wrapper">
          <input type="hidden" name="rating" id="ratingValue" required>

          <div class="luxury-star-select">
            @for($i = 1; $i <= 5; $i++)
              <i class="far fa-star luxury-star" data-value="{{ $i }}"></i>
            @endfor
          </div>

          <span class="luxury-rating-hint" id="ratingHint">
            {{ app()->getLocale() === 'mm' ? 'ကျေးဇူးပြု၍ရွေးချယ်ပါ' : 'Please select a rating' }}
          </span>
        </div>
      </div>

      <!-- REVIEW TEXT -->
      <div class="luxury-textarea-field">
        <label class="luxury-field-label">
          {{ app()->getLocale() === 'mm' ? 'သုံးသပ်ချက်' : 'Your Review' }}
          <span class="luxury-required">*</span>
        </label>

        <textarea
          name="message"
          class="luxury-review-textarea"
          placeholder="{{ app()->getLocale()==='mm' ? 'ဤထုတ်ကုန်နှင့်ပတ်သက်ပြီးသင်ဘာထင်ပါသလဲ...' : 'What did you think about this product?...' }}"
          required
          maxlength="500"
        ></textarea>

        <div class="luxury-char-counter">
          <span id="charCount">0</span> / 500
        </div>
      </div>

      <!-- FORM ACTIONS -->
      <div class="luxury-form-actions">
        <button type="button" class="luxury-cancel-btn" id="closeModalBtn">
          {{ app()->getLocale() === 'mm' ? 'မလုပ်တော့ပါ' : 'Cancel' }}
        </button>
        <button type="submit" class="luxury-submit-review">
          <i class="fas fa-paper-plane me-2"></i>
          {{ app()->getLocale()==='mm' ? 'တင်သွင်းမည်' : 'Submit Review' }}
        </button>
      </div>
    </form>
  </div>
</div>
<!-- ========================================= -->
@endauth
