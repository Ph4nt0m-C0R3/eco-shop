<style>
.testimonial {
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
}

.testimonial-card {
    background: #ffffff;
    border-radius: 22px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
    padding: 35px;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid transparent;
    transition: all 0.3s ease;
}

.testimonial-card:hover {
    transform: translateY(-6px);
    border: 1px solid #81c408;
    box-shadow: 0 0 0 4px rgba(129, 196, 8, 0.15);
    transform: translateY(-6px);
}

/* PRODUCT SECTION */
.testimonial-product {
    display: flex;
    align-items: center;
    gap: 18px;
    margin-bottom: 25px;
}

.testimonial-product img {
    width: 85px;
    height: 85px;
    object-fit: cover;
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    background: #f8f9fa;
}

.owl-carousel .owl-item img {
    width: auto;
}

.owl-carousel .owl-item .user-avatar img {
    width: 100% !important;
    height: 100%;
}

/* REVIEW TEXT */
.testimonial-text {
    font-size: 15px;
    color: #444;
    line-height: 1.7;
    flex-grow: 1;

    display: -webkit-box;
    -webkit-line-clamp: 1;   /* number of lines */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* USER SECTION */
.testimonial-user img {
    width: 65px;
    height: 65px;
    object-fit: cover;
    border-radius: 50%;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}

.testimonial-user {
    border-top: 1px solid #f1f1f1;
    padding-top: 20px;
}

/* USER SECTION */
.testimonial-user {
    display: flex;
    align-items: center;
    gap: 15px;
    border-top: 1px solid #f1f1f1;
    padding-top: 22px;
}

/* Avatar container */
.user-avatar {
    position: relative;
    width: 70px;
    height: 70px;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

/* Verified badge */
.user-badge {
    position: absolute;
    bottom: -2px;
    right: -2px;
    background: #28a745;
    color: #fff;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
}

html[lang="mm"] .testimonial-text {
    line-height: 2.1;
}

html[lang="mm"] .testimonial-user h6 {
    line-height: 1.9;
}

html[lang="mm"] .text-uppercase {
    letter-spacing: 0; /* uppercase spacing looks bad in Burmese */
}

html[lang="mm"] h2,
html[lang="mm"] h6 {
    line-height: 1.8;
}
</style>

<!-- Premium Testimonial Section -->
<div class="container-fluid testimonial py-5">
    <div class="container py-5">

        <div class="text-center mb-5">

            <h6 class="text-primary fw-bold text-uppercase">
                {{ app()->getLocale() === 'mm'
                    ? 'ဖောက်သည်များ၏ သုံးသပ်ချက်များ'
                    : 'Customer Reviews' }}
            </h6>

            <h2 class="fw-bold">
                {{ app()->getLocale() === 'mm'
                    ? 'ကျွန်ုပ်တို့၏ ဖောက်သည်များ ပြောကြားသောအမြင်များ'
                    : 'What Our Customers Say' }}
            </h2>

        </div>

        <div class="owl-carousel testimonial-carousel">

            @forelse($testimonials as $review)

            <div class="testimonial-card">

                {{-- Product Info --}}
                <a href="{{ route('product.show', $review->product->id) }}"
                class="testimonial-product text-decoration-none text-dark">

                    <img
                        src="{{ $review->product->primaryImage
                            ? asset('storage/'.$review->product->primaryImage->image)
                            : asset('default/no-image.png') }}"
                        alt="{{ $review->product->display_name }}"
                    >

                    <div>
                        <div class="fw-bold small">
                            {{ $review->product->display_name }}
                        </div>

                        <div>
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                        </div>
                    </div>

                </a>

                <hr>

                {{-- Review Text --}}
                <p class="testimonial-text">
                    "{{ \Illuminate\Support\Str::limit($review->message, 150) }}"
                </p>

                {{-- User Info --}}
                <div class="testimonial-user">

                    <div class="user-avatar">
                        <img
                            src="{{ $review->user->image
                                ? asset('storage/'.$review->user->image)
                                : asset('user/img/avatar.jpg') }}"
                            alt="{{ $review->user->name }}"
                        >

                        <div class="user-badge">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>

                    <div>
                        <h6 class="mb-1 fw-bold">
                            {{ $review->user->name }}
                        </h6>

                        <small class="text-muted">
                            {{ app()->getLocale() === 'mm'
                                ? 'အတည်ပြုထားသော ဝယ်ယူသူ'
                                : 'Verified Buyer' }}
                        </small>
                    </div>

                </div>

            </div>

            @empty
                <div class="text-center w-100">
                    <p>
                        {{ app()->getLocale() === 'mm'
                            ? 'သုံးသပ်ချက် မရှိသေးပါ။'
                            : 'No reviews available yet.' }}
                    </p>
                </div>
            @endforelse

        </div>

    </div>
</div>

<script>
    $('.testimonial-carousel').owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 5000,
        smartSpeed: 800,
        responsive:{
            0:{ items:1 },
            768:{ items:2 },
            1200:{ items:3 }
        }
    });
</script>
