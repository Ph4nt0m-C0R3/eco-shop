@extends('user.layouts.master')

@section('content')

<style>
    :root {
        --about-dark: #1a3a00;
        --about-sage: #81c408;
        --about-cream: #f8fff0;
        --about-border: rgba(129, 196, 8, 0.2);
    }

    .about-wrapper {
        background-color: var(--about-cream);
        color: var(--about-dark);
        padding-bottom: 100px;
    }

    /* Elegant Hero without images */
    .about-header {
        padding: 120px 0 80px;
        text-align: center;
        background: radial-gradient(circle at top right, rgba(129, 196, 8, 0.05) 0%, var(--about-cream) 50%);
        border-bottom: 1px solid rgba(129, 196, 8, 0.1);
    }

    .about-header h1 {
        font-weight: 800;
        letter-spacing: -1px;
        font-size: 4rem;
        margin-bottom: 20px;
    }

    .about-tagline {
        color: var(--about-sage);
        text-transform: uppercase;
        letter-spacing: 4px;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 15px;
        display: block;
    }

    .lead-text {
        font-size: 1.4rem;
        line-height: 1.8;
        max-width: 800px;
        margin: 0 auto;
        font-weight: 300;
    }

    /* Minimalist Cards */
    .mission-card {
        background: #ffffff;
        border: 1px solid var(--about-border);
        border-radius: 40px;
        padding: 50px;
        height: 100%;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
    }

    .mission-card:hover {
        border-color: var(--about-sage);
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(129, 196, 8, 0.08);
    }

    .mission-card h5 {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .mission-card p {
        color: #666;
        line-height: 1.7;
        margin-bottom: 0;
    }

    .icon-box {
        width: 50px;
        height: 50px;
        background: rgba(129, 196, 8, 0.1);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
        color: var(--about-sage);
        font-size: 1.2rem;
    }

    .mission-card[style*="background: var(--about-dark)"] {
        background: linear-gradient(135deg, var(--about-dark), #2e7d32) !important;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .mission-card[style*="background: var(--about-dark)"]::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--about-sage);
    }

    .text-success {
        color: var(--about-sage) !important;
    }

    /* Trust Section Stripe */
    .trust-section {
        border-top: 1px solid var(--about-border);
        border-bottom: 1px solid var(--about-border);
        padding: 60px 0;
        margin-top: 80px;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--about-sage);
        display: block;
    }

    .stat-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        color: #666;
    }

    .btn-dark {
        background: var(--about-dark);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-dark:hover {
        background: var(--about-sage);
        box-shadow: 0 10px 20px rgba(129, 196, 8, 0.3);
        transform: translateY(-2px);
    }

    /* Mobile spacing fix */
    @media (max-width: 767.98px) {

        /* Push content away from screen edges */
        .about-header,
        .about-wrapper .container {
            padding-left: 25px;
            padding-right: 25px;
        }

        /* Reduce huge hero padding */
        .about-header {
            padding: 80px 0 50px;
        }

        .about-header h1 {
            font-size: 2.5rem;
        }

        .lead-text {
            font-size: 1.1rem;
        }

        /* Make cards less chunky on mobile */
        .mission-card {
            padding: 25px;
            border-radius: 25px;
        }

        /* Stats section spacing */
        .trust-section {
            padding-left: 20px;
            padding-right: 20px;
        }

        /* CTA button spacing */
        .btn.rounded-pill {
            padding-left: 25px !important;
            padding-right: 25px !important;
            border-radius: 25px;
        }
    }
</style>

@include('user.pages.partials.page-header', [
    'title' => __('about-us.label'),
    'breadcrumbs' => [
        [
            'label' => __('pages'),
            'icon'  => 'fas fa-layer-group',
            'url'   => url('/pages')
        ],
        [
            'label' => __('about-us.label'),
            'icon'  => 'fas fa-leaf'
        ]
    ]
])

<div class="about-wrapper">
    {{-- 🌿 Hero Section --}}
    <header class="about-header">
        <div class="container">
            <span class="about-tagline">{{ __('about-us.label') }}</span>
            <h1 class="display-2">{!! __('about-us.title') !!}</h1>
            <p class="lead-text">
            {!! __('about-us.subtitle', ['app' => setting('app_name', 'EcoShop')]) !!}
            </p>
        </div>
    </header>

    <div class="container">
        <div class="row g-4 justify-content-center">
            {{-- Mission --}}
            <div class="col-md-5">
                <div class="mission-card">
                    <div class="icon-box">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h5>{{ __('about-us.mission_title') }}</h5>
                    <p>{{ __('about-us.mission_desc') }}</p>
                </div>
            </div>

            {{-- Vision --}}
            <div class="col-md-5">
                <div class="mission-card">
                    <div class="icon-box">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <h5>{{ __('about-us.vision_title') }}</h5>
                    <p>{{ __('about-us.vision_desc') }}</p>
                </div>
            </div>

            {{-- Why Us (Full Width) --}}
            <div class="col-md-10 mt-4">
                <div class="mission-card" style="background: var(--about-dark); color: #fff;">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h5 class="text-white">
                            <i class="fas fa-heart text-success me-3"></i>
                            {{ __('about-us.why_title', ['app' => setting('app_name', 'EcoShop')]) }}
                            </h5>

                            <p class="text-white opacity-75">
                            {{ __('about-us.why_desc') }}
                            </p>
                        </div>
                        <div class="col-lg-4 text-end d-none d-lg-block">
                            <i class="fas fa-quote-right" style="font-size: 5rem; opacity: 0.1;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 📊 Minimalist Trust Stats --}}
        <div class="row trust-section text-center">
            <div class="col-md-4">
                <span class="stat-number">100%</span>
                <span class="stat-label">{{ __('about-us.stats_plastic') }}</span>
            </div>
            <div class="col-md-4">
                <span class="stat-number"> Ethical </span>
                <span class="stat-label">{{ __('about-us.stats_ethical') }}</span>
            </div>
            <div class="col-md-4">
                <span class="stat-number">10k+</span>
                <span class="stat-label">{{ __('about-us.stats_trees') }}</span>
            </div>
        </div>

        {{-- Final CTA --}}
        <div class="text-center mt-5 pt-4">
            <p class="mb-4 text-muted">{{ __('about-us.cta_text') }}</p>

            <a href="/shop" class="btn btn-dark rounded-pill px-5 py-3 fw-bold">
            {{ __('about-us.cta_button') }}
            </a>
        </div>
    </div>
</div>

@endsection
