<style>
/* =================================
   ECO ESSENCE — MYANMAR ALIGNMENT
================================= */

html[lang="mm"] .eco-essence {
    line-height: 1.9; /* match master body */
}

/* Tag */
html[lang="mm"] .essence-tag {
    font-size: 1rem;
    letter-spacing: 0;
    text-transform: none;
    line-height: 1.8;
}

/* Title */
html[lang="mm"] .essence-title {
    line-height: 1.4; /* match master heading */
    letter-spacing: 0;
}

html[lang="mm"] .essence-title span {
    line-height: 1.45;
}

/* Description */
html[lang="mm"] .essence-description {
    font-size: 1rem;
    line-height: 1.95; /* master paragraph rhythm */
    max-width: 520px; /* slightly wider for MM text */
}

/* Buttons */
html[lang="mm"] .essence-btn-primary,
html[lang="mm"] .essence-btn-outline {
    font-size: 1rem;
    line-height: 1.8;
    padding: 16px 36px; /* avoid cramped Burmese */
}

/* Floating badge */
html[lang="mm"] .essence-card {
    font-size: 0.95rem;
    line-height: 1.9;
}



/* ===== ECO ESSENCE BANNER ===== */
.eco-essence {
    background: #fafbf8;
    padding: 100px 0; /* Better vertical spacing */
    position: relative;
    overflow: hidden;
    font-family: system-ui, -apple-system, 'Noto Sans Myanmar', 'Pyidaungsu', sans-serif;
}

.eco-essence::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 80% 40%, rgba(129, 196, 8, 0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 20% 70%, rgba(255, 152, 0, 0.06) 0%, transparent 50%);
    pointer-events: none;
}

.essence-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 37px; /* Match container style */
    width: 100%;
    position: relative;
    z-index: 2;
}

/* main grid */
.essence-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px; /* More breathing space */
    align-items: center;
}

/* left content */
.essence-content {
    padding-right: 30px;
}

.essence-sup {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 24px;
}

.essence-line {
    width: 50px;
    height: 2px;
    background: #ff9800;
}

.essence-tag {
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #81c408;
}

.essence-title {
    font-size: clamp(2rem, 4vw, 3.4rem);
    font-weight: 900;
    line-height: 1.15;
    color: #1c2e2e;
    margin-bottom: 20px;
}

.essence-title span {
    display: block;
    font-weight: 900;
    line-height: 1.2;
    font-size: inherit;
    padding: 20px 0;
    background: linear-gradient(90deg, #689a06, #81c408);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.essence-description {
    font-size: clamp(1rem, 1.4vw, 1.1rem);
    line-height: 1.7;
    color: #4f6262;
    margin-bottom: 45px;
    max-width: 480px;
}

/* buttons */
.essence-buttons {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.essence-btn-primary,
.essence-btn-outline {
    padding: 14px 34px;
    font-weight: 600;
    font-size: 16px;
    border-radius: 40px;
    transition: 0.25s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.essence-btn-primary {
    background: #81c408;
    color: #fff;
    border: none;
    box-shadow: 0 10px 20px -8px rgba(129, 196, 8, 0.4);
}

.essence-btn-primary:hover {
    background: #6aa306;
    color: #fff;
    transform: translateY(-2px);
}

.essence-btn-outline {
    background: transparent;
    border: 2px solid #ff9800;
    color: #ff9800;
}

.essence-btn-outline:hover {
    background: #ff9800;
    color: #fff;
    transform: translateY(-2px);
}

/* right visual */
.essence-visual {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

.essence-circle {
    width: 100%;
    max-width: 440px;
    aspect-ratio: 1/1;
    background: #e9f0da;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 30px 50px -20px rgba(0, 0, 0, 0.15);
}

.circle-image {
    width: 85%;
    height: 85%;
    border-radius: 50%;
    overflow: hidden;
}

.circle-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.circle-image:hover img {
    transform: scale(1.05);
}

/* floating badge */
.essence-card {
    position: absolute;
    bottom: 5%;
    right: -5%;
    background: rgba(255, 255, 255, 0.95);
    padding: 14px 26px;
    border-radius: 60px;
    box-shadow: 0 20px 30px -10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 12px;
    border: 1px solid rgba(129,196,8,0.2);
    font-weight: 600;
    color: #1c2e2e;
}

.essence-card-icon {
    font-size: 20px;
    color: #81c408;
}

/* responsive */
@media (max-width: 992px) {
    .eco-essence {
        padding: 80px 0;
    }

    .essence-grid {
        grid-template-columns: 1fr;
        gap: 50px;
        text-align: center;
    }

    .essence-content {
        padding-right: 0;
    }

    .essence-description {
        margin-left: auto;
        margin-right: auto;
    }

    .essence-sup,
    .essence-buttons {
        justify-content: center;
    }

    .essence-card {
        position: static;
        margin-top: 30px;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .essence-btn-primary,
    .essence-btn-outline {
        padding: 12px 24px;
        font-size: 15px;
    }

    .essence-circle {
        max-width: 300px;
    }
}

@media (max-width: 768px) {

    html[lang="mm"] .essence-title {
        line-height: 1.45;
    }

    html[lang="mm"] .essence-description {
        font-size: 0.98rem;
    }

    html[lang="mm"] .essence-btn-primary,
    html[lang="mm"] .essence-btn-outline {
        width: 100%;
        justify-content: center;
    }

}
</style>

<!-- Eco Essence Banner -->
<div class="eco-essence">
    <div class="essence-container">
        <div class="essence-grid">

            <!-- Left content -->
            <div class="essence-content">
                <div class="essence-sup">
                    <span class="essence-line"></span>
                    <span class="essence-tag">
                        {{ app()->getLocale() === 'mm' ? 'အသိရှိသော ဝယ်ယူမှု' : 'Conscious Shopping' }}
                    </span>
                </div>

                <h1 class="essence-title">
                    @if(app()->getLocale() === 'mm')
                        ပိုမိုကောင်းမွန်သော ရွေးချယ်မှု။
                        <span>တောက်ပသော အနာဂတ်။</span>
                    @else
                        Better Choices.
                        <span>Brighter Future.</span>
                    @endif
                </h1>

                <p class="essence-description">
                    {{ app()->getLocale() === 'mm'
                        ? 'သဘာဝနှင့် သဟဇာတဖြစ်သော ထုတ်ကုန်များဖြင့် သင့်နေ့စဉ်ဘဝကို အဆင့်မြှင့်တင်လိုက်ပါ။ အရည်အသွေးမြင့် ထုတ်ကုန်များကို တာဝန်ယူမှုဖြင့် ထုတ်လုပ်ထားပါသည်။'
                        : 'Upgrade your everyday lifestyle with responsibly sourced, eco-friendly essentials designed for modern living. Sustainable products without compromise.'
                    }}
                </p>

                <div class="essence-buttons">
                    <a href="{{ route('shop.index') }}" class="essence-btn-primary">
                        <i class="fas fa-bag-shopping"></i>
                        {{ app()->getLocale() === 'mm' ? 'စျေးဝယ်ရန်' : 'Shop Collection' }}
                    </a>

                    <a href="{{ route('about.us') }}" class="essence-btn-outline">
                        <i class="fas fa-circle-info"></i>
                        {{ app()->getLocale() === 'mm' ? 'ကျွန်ုပ်တို့အကြောင်း' : 'Our Mission' }}
                    </a>
                </div>
            </div>

            <!-- Right visual -->
            <div class="essence-visual">
                <div class="essence-circle">
                    <div class="circle-image">
                        <img src="{{ asset('storage/frontend/banner.jpg') }}" alt="Sustainable Products">
                    </div>
                </div>

                <!-- Floating badge -->
                <div class="essence-card">
                    <span class="essence-card-icon">
                        <i class="fas fa-leaf"></i>
                    </span>
                    <span>
                        {{ app()->getLocale() === 'mm'
                            ? 'တာဝန်ယူ ထုတ်လုပ် • သဘာဝနှင့် သဟဇာတ'
                            : 'Ethically Sourced & Planet Friendly'
                        }}
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>
