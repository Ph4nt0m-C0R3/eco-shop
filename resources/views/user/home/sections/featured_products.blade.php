<style>
/* ===============================
   PREMIUM HERO SECTION HEADER
================================ */

.eco-section-header {
    position: relative;
    background: #81c408;
    border-radius: 20px;
    padding: 70px 80px;
    margin-bottom: 30px;
    color: white;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-family: "Noto Sans Myanmar", system-ui, sans-serif;
}

/* LEFT CONTENT */
.eco-header-content {
    max-width: 520px;
    z-index: 2;
}

/* Small badge */
.eco-header-badge {
    display: inline-block;
    background: rgba(255,255,255,0.15);
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 18px;
}

/* Title */
.eco-section-title {
    font-size: 56px;
    font-weight: 900;
    line-height: 1.1;
    margin-bottom: 16px;
    letter-spacing: -1px;
}

/* Subtitle */
.eco-section-subtitle {
    font-size: 18px;
    opacity: 0.9;
    margin-bottom: 25px;
}

/* CTA Button */
.eco-header-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ff9800;
    color: white;
    padding: 14px 26px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.25s;
}

.eco-header-btn:hover {
    background: #e68900;
}

/* BIG BACKGROUND TEXT */
.eco-section-header::after {
    content: "FEATURED";
    position: absolute;
    right: 30px;
    bottom: -20px;
    font-size: 160px;
    font-weight: 900;
    color: rgba(255,255,255,0.06);
    pointer-events: none;
}

/* FLOATING DECORATIVE SHAPES (no gradients) */
.eco-section-header::before {
    content: "";
    position: absolute;
    right: -80px;
    top: -80px;
    width: 260px;
    height: 260px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
}

.eco-header-visual {
    position: relative;
    width: 240px;
    height: 240px;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 30px 60px rgba(0,0,0,0.18);
    z-index: 2;
}

/* Product image */
.eco-header-visual img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Handpicked badge */
.eco-visual-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    background: #ff9800;
    color: white;
    padding: 6px 12px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
}

/* soft glass overlay */
.eco-header-visual::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.08);
}

/* Mobile */
@media (max-width: 900px) {
    .eco-header-visual {
        width: 100%;
        height: 200px;
    }
}

/* Mobile */
@media (max-width: 900px) {
    .eco-section-header {
        flex-direction: column;
        align-items: flex-start;
        padding: 50px 30px;
        gap: 30px;
    }

    .eco-section-title {
        font-size: 38px;
    }

    .eco-header-visual {
        width: 100%;
        height: 120px;
    }
}

/* Carousel spacing */
.eco-products-carousel .eco-carousel-item {
    padding: 10px;
}

.eco-products-carousel .eco-carousel-item .eco-product-card {
    height: 100%;
}

/* Remove old template effects */
.vesitable-item {
    box-shadow: none !important;
    background: transparent !important;
}

html[lang="mm"] .eco-section-title {
    line-height: 1.7;
    letter-spacing: 0;
}

html[lang="mm"] .eco-section-subtitle {
    line-height: 2;
}

html[lang="mm"] .eco-header-badge {
    line-height: 1.8;
}

html[lang="mm"] .eco-header-btn {
    line-height: 1.9;
}
</style>


<!-- Featured Products Start -->
<section class="eco-featured-products pt-5">

    <div class="container">

        <!-- Section Header -->
        <div class="eco-section-header">

            <!-- LEFT -->
            <div class="eco-header-content">

                <!-- Badge -->
                <div class="eco-header-badge">
                    {{ app()->getLocale() === 'mm'
                        ? 'အထူးရွေးချယ်ထားသော ပစ္စည်းများ'
                        : 'Featured Collection' }}
                </div>

                <!-- Title -->
                <h2 class="eco-section-title">
                    {{ app()->getLocale() === 'mm'
                        ? 'အထူးပစ္စည်းများ'
                        : 'Featured Products' }}
                </h2>

                <!-- Description -->
                <p class="eco-section-subtitle">
                    {{ app()->getLocale() === 'mm'
                        ? 'နောက်ဆုံးထွက်ပစ္စည်းများနှင့် လူကြိုက်များသော ပစ္စည်းများကို သင့်အတွက် အထူးရွေးချယ်ပေးထားပါသည်။'
                        : 'Discover our latest arrivals and customer favorites curated for the best shopping experience.' }}
                </p>

                <!-- CTA -->
                <a href="{{ route('shop.index') }}" class="eco-header-btn">
                    {{ app()->getLocale() === 'mm'
                        ? 'ပစ္စည်းများ ကြည့်ရှုရန် →'
                        : 'Explore Collection →' }}
                </a>

            </div>

            <!-- RIGHT VISUAL -->
            <div class="eco-header-visual">

                <img src="{{ asset('storage/frontend/featured.jpg') }}"
                    alt="{{ app()->getLocale() === 'mm' ? 'အထူးပစ္စည်း' : 'Featured Product' }}">

                <!-- Handpicked badge -->
                <div class="eco-visual-badge">
                    {{ app()->getLocale() === 'mm'
                        ? 'လက်ရွေးစင်'
                        : 'Handpicked' }}
                </div>

            </div>

        </div>

        <!-- IMPORTANT: use vesitable class so your CSS applies -->
        <div class="vesitable">

            <div class="owl-carousel eco-products-carousel">

                @forelse($featuredProducts as $product)

                    <div class="eco-carousel-item">
                        @include('user.components.products.card', [
                            'product' => $product
                        ])
                    </div>

                @empty

                    <div class="eco-empty-state text-center w-100 py-5">
                        <h5>No products available</h5>
                        <p>Please check back later.</p>
                    </div>

                @endforelse

            </div>

        </div>

    </div>

</section>
<!-- Featured Products End -->
