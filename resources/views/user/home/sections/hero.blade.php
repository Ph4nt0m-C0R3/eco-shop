<!-- Modern Eco Hero -->
<section class="eco-hero-modern">
    <div class="hero-container">
        <!-- Background Elements -->
        <div class="bg-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>

        <div class="hero-content">
            <!-- Left Side -->
            <div class="hero-text">
                <div class="tagline">
                    <span class="tag-badge">
                        <i class="fas fa-leaf"></i>
                        {{ app()->getLocale() === 'mm' ? 'သဘာဝနှင့်သဟဇာတဖြစ်သော လူနေမှုဘဝ' : 'Eco-Friendly & Sustainable Lifestyle' }}
                    </span>
                </div>

                <h1 class="hero-title">
                    {{ app()->getLocale() === 'mm' ? 'ပိုမိုထိရောက်စွာ ဈေးဝယ်ပါ' : 'Shop Smarter' }}
                    <span class="highlight">
                        {{ app()->getLocale() === 'mm' ? 'ပိုမိုစိမ်းလန်းစွာ နေထိုင်ပါ' : 'Live Greener' }}
                    </span>
                    {{ app()->getLocale() === 'mm' ? 'နေ့စဉ်နှင့်အမျှ' : 'Every Day' }}
                </h1>

                <p class="hero-description">
                    {{ app()->getLocale() === 'mm'
                        ? 'သင့်အိမ်၊ အဝတ်အစား၊ ကိုယ်ရည်ကိုယ်သွေး ထိန်းသိမ်းမှု၊ အစားအစာ၊ ကလေးများနှင့် ပြင်ပလူနေမှုဘဝအတွက် သဘာဝပတ်ဝန်းကျင်ကို ထိခိုက်မှုနည်းသော ထုတ်ကုန်များကို တစ်နေရာတည်းမှာ ရှာဖွေပါ။'
                        : 'Discover eco-conscious products for your home, fashion, personal care, food, kids, and outdoor lifestyle — all in one place.'
                    }}
                </p>

                <!-- Search Bar -->
                <div class="search-section">
                    <div class="search-wrapper">
                        <div class="search-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text"
                               placeholder="{{ app()->getLocale() === 'mm' ? 'သဘာဝထုတ်ကုန်များ ရှာဖွေရန်...' : 'Search for organic products...' }}"
                               class="search-input">
                        <button class="search-btn">
                            {{ app()->getLocale() === 'mm' ? 'စူးစမ်းလေ့လာပါ' : 'Explore Now' }}
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                    <div class="quick-links">
                        <span>
                            {{ app()->getLocale() === 'mm' ? 'အမြန်လင့်များ - ' : 'Quick links :' }}
                        </span>

                        <a href="#"
                        data-search-en="Eco Home & Living"
                        data-search-mm="အိမ်သုံးပစ္စည်းများ">
                            {{ app()->getLocale() === 'mm' ? 'အိမ်' : 'Home' }}
                        </a>

                        <a href="#"
                        data-search-en="Sustainable Fashion"
                        data-search-mm="သဘာဝနှင့်လိုက်ဖက်သော ဖက်ရှင်">
                            {{ app()->getLocale() === 'mm' ? 'ဖက်ရှင်' : 'Fashion' }}
                        </a>

                        <a href="#"
                        data-search-en="Natural Personal Care"
                        data-search-mm="သဘာဝကိုယ်ရေးကိုယ်တာအသုံးအဆောင်">
                            {{ app()->getLocale() === 'mm' ? 'အသုံးအဆောင်' : 'Care' }}
                        </a>

                        <a href="#"
                        data-search-en="Zero-Waste Products"
                        data-search-mm="အမှိုက်လျော့ချရေးပစ္စည်းများ">
                            {{ app()->getLocale() === 'mm' ? 'အမှိုက်လျော့' : 'Zero-Waste' }}
                        </a>

                        <a href="#"
                        data-search-en="Organic Food & Beverages"
                        data-search-mm="သဘာဝအစားအစာနှင့်အဖျော်ယမကာ">
                            {{ app()->getLocale() === 'mm' ? 'အစားအစာ' : 'Food' }}
                        </a>

                        <a href="#"
                        data-search-en="Gardening & Outdoor"
                        data-search-mm="ဥယျာဉ်နှင့်ပြင်ပအသုံးအဆောင်">
                            {{ app()->getLocale() === 'mm' ? 'ဥယျာဉ်' : 'Garden' }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Side - Visual -->
            <div class="hero-visual">
                <!-- Main Image Container -->
                <div class="image-main">
                    <img src="{{ asset('storage/frontend/hero.jpg') }}"
                         alt="Hero Image"
                         class="main-img">
                    <div class="image-overlay"></div>

                    <!-- Floating Badges -->
                    <div class="floating-badge fresh-badge">
                        <i class="fas fa-star"></i>
                        {{ app()->getLocale() === 'mm' ? 'ထိပ်တန်း Eco အရွေးများ' : 'Top Eco Picks' }}
                    </div>
                    <div class="floating-badge organic-badge">
                        <i class="fas fa-check-circle"></i>
                        {{ app()->getLocale() === 'mm' ? 'တည်တံ့သောရွေးချယ်မှု' : 'Sustainable Choice' }}
                    </div>
                </div>

                <!-- Floating Product Cards -->
                <div class="product-card card-1">
                    <div class="card-image">
                        <img src="{{ asset('storage/frontend/fashion.jpg') }}"
                             alt="Sustainable Fashion">
                    </div>
                    <div class="card-label">
                        {{ app()->getLocale() === 'mm' ? 'တည်တံ့သော ဖက်ရှင်' : 'Sustainable Fashion' }}
                    </div>
                </div>

                <div class="product-card card-2">
                    <div class="card-image">
                        <img src="{{ asset('storage/frontend/eco-home.jpg') }}"
                             alt="Eco Home & Living">
                    </div>
                    <div class="card-label">
                        {{ app()->getLocale() === 'mm' ? 'Eco အိမ်နှင့် နေထိုင်မှု' : 'Eco Home & Living' }}
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="visual-cta">
                    <a href="{{ route('shop.index') }}" class="cta-btn">
                        <span>{{ app()->getLocale() === 'mm' ? 'အခုပဲ ဝယ်ယူပါ' : 'Shop Now' }}</span>
                        <i class="fas fa-shopping-basket"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Full Width Stats Section -->
        <div class="hero-stats-fullwidth mm-friendly">
            <div class="stats-container">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="stat-info">
                            <h4>{{ app()->getLocale() === 'mm' ? 'Eco အိမ်နှင့် နေထိုင်မှု' : 'Eco Home & Living' }}</h4>
                            <p>{{ app()->getLocale() === 'mm' ? 'သင့်အိမ်အတွက် တည်တံ့သော လိုအပ်ချက်များ' : 'Sustainable essentials for your home' }}</p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <div class="stat-info">
                            <h4>{{ app()->getLocale() === 'mm' ? 'တည်တံ့သော ဖက်ရှင်' : 'Sustainable Fashion' }}</h4>
                            <p>{{ app()->getLocale() === 'mm' ? 'သန့်ရှင်းပြီး ခေတ်မီသော ဖက်ရှင်ကို ဝတ်ဆင်ပါ' : 'Wear clean and stylish fashion' }}</p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-recycle"></i>
                        </div>
                        <div class="stat-info">
                            <h4>{{ app()->getLocale() === 'mm' ? 'အမှိုက်သုည ထုတ်ကုန်များ' : 'Zero-Waste Products' }}</h4>
                            <p>{{ app()->getLocale() === 'mm' ? 'စိမ်းလန်းသော အနာဂတ်အတွက် ပြန်လည်အသုံးပြုနိုင်သော ပစ္စည်းများ' : 'Reusable items for a greener future' }}</p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <div class="stat-info">
                            <h4>{{ app()->getLocale() === 'mm' ? 'သဘာဝ အစားအစာနှင့် အဖျော်ယမကာများ' : 'Organic Food & Beverages' }}</h4>
                            <p>{{ app()->getLocale() === 'mm' ? 'သင့်လူနေမှုပုံစံအတွက် ကျန်းမာသော သဘာဝအစားအစာ' : 'Healthy natural food for your lifestyle' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* =========================
   BASE RESET
========================= */
.eco-hero-modern * {
    box-sizing: border-box;
}

.eco-hero-modern {
    position: relative;
    padding: 0 17px;
    padding-bottom: 30px;
    background: linear-gradient(135deg, #ffffff 0%, #f8fff0 100%);
    overflow: hidden;
    margin-top: var(--navbar-height);
}

/* Container */
.hero-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 25px;
    position: relative;
    z-index: 2;
}

/* Background Shapes */
.bg-shapes {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 1;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(129, 196, 8, 0.12), transparent 70%);
    animation: shapeFloat 20s infinite linear;
}

.shape-1 {
    width: 320px;
    height: 320px;
    top: -150px;
    right: -150px;
}

.shape-2 {
    width: 240px;
    height: 240px;
    bottom: -120px;
    left: -120px;
    animation-delay: -10s;
}

.shape-3 {
    width: 170px;
    height: 170px;
    top: 35%;
    left: 8%;
    animation-delay: -5s;
}

/* =================================
   HERO — MYANMAR ALIGN WITH MASTER
================================= */

html[lang="mm"] .eco-hero-modern {
    line-height: 1.9; /* match body rhythm */
}

/* Title */
html[lang="mm"] .hero-title {
    line-height: 1.4; /* match master heading rule */
    letter-spacing: 0;
}

/* Highlight line */
html[lang="mm"] .highlight {
    line-height: 1.45;
}

/* Description */
html[lang="mm"] .hero-description {
    line-height: 1.95; /* same as master p */
    font-size: 1rem;
}

/* Tag badge */
html[lang="mm"] .tag-badge {
    font-size: 1rem;
    line-height: 1.8;
    padding-top: 12px;
    padding-bottom: 12px;
}

/* Search */
html[lang="mm"] .search-input {
    font-size: 1rem;
    line-height: 1.9;
}

html[lang="mm"] .search-btn {
    font-size: 1rem;
    line-height: 1.8;
}

/* Quick links */
html[lang="mm"] .quick-links {
    font-size: 0.95rem;
    line-height: 1.9;
}

/* Floating badges */
html[lang="mm"] .floating-badge {
    font-size: 0.95rem;
    line-height: 1.8;
}

/* Product card label */
html[lang="mm"] .card-label {
    font-size: 0.95rem;
    line-height: 1.7;
}

/* Stats section */
html[lang="mm"] .stat-info h4 {
    font-size: 1rem;        /* match master scale */
    line-height: 1.6;
}

html[lang="mm"] .stat-info p {
    font-size: 0.95rem;
    line-height: 1.9;
}

.hero-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    padding: 0px 30px;
}

/* LEFT TEXT */
.hero-text {
    max-width: 620px;
}

.tagline {
    margin-bottom: 18px;
}

.tag-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(90deg, #689a06, #81c408);
    color: white;
    padding: 10px 22px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 8px 20px rgba(129, 196, 8, 0.25);
    animation: badgePulse 2s infinite alternate;
}

/* TITLE */
.hero-title {
    font-size: clamp(2rem, 4vw, 3.6rem);
    font-weight: 900;
    line-height: 1.1;
    color: #1a3a00;
    margin-bottom: 16px;
}

.highlight {
    background: linear-gradient(90deg, #689a06, #81c408);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: block;
    line-height: 1.15;
    padding: 10px 0;
}

/* DESCRIPTION */
.hero-description {
    font-size: clamp(1rem, 1.4vw, 1.1rem);
    line-height: 1.7;
    color: #5a6c5a;
    margin-bottom: 25px;
    max-width: 520px;
}

/* =========================
   SEARCH SECTION
========================= */
.search-section {
    margin-top: 25px;
}

.search-wrapper {
    display: flex;
    align-items: center;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e8f0d0;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.07);
    width: 100%;
}

.search-icon {
    padding: 0 18px;
    display: flex;
    align-items: center;
    color: #81c408;
    font-size: 18px;
}

.search-input {
    flex: 1;
    border: none;
    padding: 18px 12px;
    font-size: 15px;
    outline: none;
    background: transparent;
    width: 100%;
}

.search-btn {
    background: linear-gradient(90deg, #689a06, #81c408);
    color: white;
    border: none;
    padding: 0 28px;
    height: 60px;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.search-btn:hover {
    background: linear-gradient(90deg, #577f05, #689a06);
    transform: translateX(3px);
}

.quick-links {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
    margin: 15px 0;
    font-size: 13px;
    color: #666;
}

.quick-links span {
    font-weight: 600;
}

.quick-links a {
    color: #689a06;
    text-decoration: none;
    padding: 6px 14px;
    border-radius: 50px;
    background: rgba(129, 196, 8, 0.12);
    transition: all 0.3s ease;
}

.quick-links a:hover {
    background: #81c408;
    color: white;
}

/* =========================
   HERO VISUAL
========================= */
.hero-visual {
    position: relative;
    margin-top: 5%;
    width: 100%;
    min-height: 520px;
}

.image-main {
    width: 100%;
    height: 430px;
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 22px 55px rgba(0, 0, 0, 0.12);
    border: 8px solid #81c408;
    position: relative;
    z-index: 2;
}

.main-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(129, 196, 8, 0.15), transparent);
}

/* Floating Badges */
.floating-badge {
    position: absolute;
    background: white;
    padding: 10px 18px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 700;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 8px;
    z-index: 3;
    animation: badgeFloat 3s ease-in-out infinite;
}

.fresh-badge {
    top: 18px;
    left: 18px;
    color: #689a06;
}

.organic-badge {
    bottom: 18px;
    right: 18px;
    color: #ff9800;
    animation-delay: 1.5s;
}

/* Product Cards */
.product-card {
    position: absolute;
    width: 155px;
    height: 155px;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    border: 5px solid white;
    z-index: 1;
    transition: all 0.4s ease;
}

.product-card:hover {
    transform: scale(1.05);
    z-index: 4;
}

.card-image {
    width: 100%;
    height: 100%;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card-label {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(104, 154, 6, 0.9);
    color: white;
    padding: 10px;
    text-align: center;
    font-size: 12px;
    font-weight: 700;
}

.card-1 {
    top: 18%;
    right: -25px;
    animation: cardFloat 7s ease-in-out infinite;
}

.card-2 {
    bottom: 10%;
    left: -25px;
    animation: cardFloat 7s ease-in-out infinite 2s;
}

/* CTA */
.visual-cta {
    display: flex;
    justify-content: center;
    position: relative;
    z-index: 5;
}

.cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: linear-gradient(90deg, #ff9800, #ffb74d);
    color: white;
    padding: 14px 35px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    font-size: 16px;
    box-shadow: 0 12px 35px rgba(255, 152, 0, 0.35);
    transition: all 0.3s ease;
}

.cta-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 45px rgba(255, 152, 0, 0.45);
}

/* =========================
   STATS SECTION (FIXED FLOW)
========================= */
.hero-stats-fullwidth {
    width: 100%;
    background: linear-gradient(90deg, rgba(255,255,255,0.96), rgba(248,255,240,0.96));
    border: 1px solid rgba(129, 196, 8, 0.12);
    border-radius: 18px;
    padding: 28px 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    backdrop-filter: blur(10px);
}

.stats-container {
    max-width: 1200px;
    margin: 0 auto;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}

.stat-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px 18px;
    background: #fff;
    border-radius: 14px;
    border: 1px solid rgba(129, 196, 8, 0.12);
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-4px);
}

.stat-icon {
    width: 52px;
    height: 52px;
    min-width: 52px;
    background: linear-gradient(135deg, #f0f8e0, #e0f0c0);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #689a06;
    font-size: 22px;
}

.stat-info h4 {
    margin: 0 0 6px;
    font-size: 15px;
    font-weight: 800;
    color: #1a3a00;
    line-height: 1.4;
}

.stat-info p {
    margin: 0;
    font-size: 13px;
    color: #555;
    line-height: 1.6;
}

/* =========================
   RESPONSIVE FIXES
========================= */
@media (max-width: 1024px) {
    .hero-content {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .hero-text {
        margin: 0 auto;
        max-width: 620px;
    }

    .hero-description {
        margin-left: 0;
        margin-right: 0;
    }

    .quick-links {
        justify-content: flex-start;
    }

    .hero-visual {
        order: -1;
        min-height: auto;
    }

    .image-main {
        height: 380px;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .product-card {
        width: 140px;
        height: 140px;
    }

    .card-1 {
        right: -15px;
    }

    .card-2 {
        left: -15px;
    }

    .hero-stats-fullwidth {
        margin-top: 30px;
    }
}

@media (max-width: 768px) {
    .hero-container {
        padding: 0 10px;
    }

    .hero-content {
        padding-left: 10px;
        padding-right: 10px;
    }

    .image-main {
        height: 320px;
    }

    .search-wrapper {
        flex-direction: column;
        border-radius: 14px;
        overflow: visible;
        box-shadow: none;
        background: transparent;
        border: none;
        gap: 12px;
    }

    .search-input {
        width: 100%;
        background: white;
        border: 1px solid #e8f0d0;
        border-radius: 12px;
        padding: 16px 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
    }

    .search-btn {
        width: 100%;
        justify-content: center;
        height: 54px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(129, 196, 8, 0.15);
    }

    .search-icon {
        display: none;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .product-card {
        display: none; /* cleaner on mobile */
    }
}

@media (max-width: 768px){

    html[lang="mm"] .hero-title {
        line-height: 1.5;
    }

    html[lang="mm"] .hero-description {
        font-size: 0.98rem;
    }

    html[lang="mm"] .search-btn {
        height: 58px; /* avoid cramped Burmese */
    }

}

@media (max-width: 480px) {
    .hero-title {
        font-size: 1.9rem;
    }

    .hero-description {
        font-size: 0.98rem;
    }

    .floating-badge {
        padding: 8px 14px;
        font-size: 11px;
    }

    .image-main {
        height: 270px;
    }

    .cta-btn {
        width: 100%;
        justify-content: center;
    }
}

/* =========================
   ANIMATIONS
========================= */
@keyframes shapeFloat {
    0% { transform: translate(0, 0) rotate(0deg); }
    100% { transform: translate(30px, 30px) rotate(360deg); }
}

@keyframes badgePulse {
    0% { box-shadow: 0 8px 20px rgba(129, 196, 8, 0.2); }
    100% { box-shadow: 0 8px 30px rgba(129, 196, 8, 0.4); }
}

@keyframes badgeFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes cardFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-18px); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize animations
    const heroText = document.querySelector('.hero-text');
    if (heroText) {
        heroText.style.opacity = '1';
    }

    // ===== HERO SEARCH FUNCTIONALITY =====
    const searchInput = document.querySelector('.search-input');
    const searchBtn = document.querySelector('.search-btn');
    const quickLinks = document.querySelectorAll('.quick-links a');

    function performSearch() {
        if (!searchInput) return;

        const keyword = searchInput.value.trim();

        if (!keyword) {
            searchInput.focus();
            return;
        }

        window.location.href = `/shop?search=${encodeURIComponent(keyword)}`;
    }

    // Explore button
    if (searchBtn) {
        searchBtn.addEventListener('click', function () {
            performSearch();
        });
    }

    // Enter key
    if (searchInput) {
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
        });
    }

    // Quick links (fill only)
    quickLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const keyword =
                document.documentElement.lang === 'mm'
                    ? this.dataset.searchMm
                    : this.dataset.searchEn;

            if (searchInput && keyword) {
                searchInput.value = keyword;
                searchInput.focus();
            }
        });
    });

    // CTA button animation
    const ctaBtn = document.querySelector('.cta-btn');
    if (ctaBtn) {
        ctaBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });

        ctaBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    }

    // Add hover effect to product cards
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '3';
        });

        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });

    // Add hover effect to stat items
    const statItems = document.querySelectorAll('.stat-item');
    statItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.zIndex = '4';
        });

        item.addEventListener('mouseleave', function() {
            this.style.zIndex = '3';
        });
    });

    // Add scroll animation trigger
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
            }
        });
    }, observerOptions);

    // Observe animated elements
    document.querySelectorAll('.hero-text > *, .product-card, .floating-badge, .stat-item').forEach(el => {
        observer.observe(el);
    });

    // Add some interactive parallax effect
    document.addEventListener('mousemove', function(e) {
        const x = (e.clientX / window.innerWidth - 0.5) * 10;
        const y = (e.clientY / window.innerHeight - 0.5) * 10;

        const imageMain = document.querySelector('.image-main');
        if (imageMain) {
            imageMain.style.transform = `translate(${x}px, ${y}px)`;
        }
    });
});
</script>
