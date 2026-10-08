@extends('user.layouts.master')

@section('content')

<style>
    :root {
        --terms-dark: #1a3a00;
        --terms-sage: #81c408;
        --terms-sand: #f8fff0;
        --terms-wood: #ff9800;
    }

    .text-success {
        color: var(--terms-sage) !important;
    }

    .terms-header {
        background-color: var(--terms-sand);
        padding: 80px 0;
        position: relative;
        overflow: hidden;
    }

    /* Decorative Cardboard Texture Background */
    .terms-header::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: url('https://www.transparenttextures.com/patterns/cardboard.png');
        opacity: 0.05;
        pointer-events: none;
    }

    .terms-label {
        color: var(--terms-sage);
        text-transform: uppercase;
        letter-spacing: 3px;
        font-weight: 800;
        font-size: 0.75rem;
    }

    .terms-image-frame {
        border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; /* Organic Blob Shape */
        overflow: hidden;
        border: 10px solid #fff;
        box-shadow: 0 15px 45px rgba(129, 196, 8, 0.1);
        border: 2px solid rgba(129, 196, 8, 0.2);
    }

    .legal-content {
        background: #fff;
        margin-top: -40px;
        border-radius: 50px 50px 0 0;
        padding: 80px 0;
    }

    .agreement-card {
        border: 1.5px solid rgba(129, 196, 8, 0.1);
        border-radius: 30px;
        padding: 45px;
        height: 100%;
        transition: all 0.3s ease;
        background: #fff;
    }

    .agreement-card:hover {
        border-color: var(--terms-sage);
        box-shadow: 0 15px 30px rgba(129, 196, 8, 0.15);
        transform: translateY(-5px);
    }

    .step-number {
        font-family: 'serif';
        font-size: 3rem;
        color: var(--terms-sage);
        opacity: 0.3;
        line-height: 1;
        margin-bottom: 15px;
    }

    .highlight-box {
        background: linear-gradient(135deg, var(--terms-sage), #689a06);
        color: #fff;
        border-radius: 40px;
        padding: 60px;
        margin-top: 50px;
        position: relative;
        overflow: hidden;
    }

    .highlight-box::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--terms-wood);
    }

    .list-eco li {
        margin-bottom: 12px;
        display: flex;
        align-items: center;
    }

    .list-eco i {
        color: var(--terms-sage);
        margin-right: 15px;
        font-size: 0.9rem;
    }

    .list-eco .text-danger {
        color: var(--terms-wood) !important;
    }

    .terms-image-frame {
        max-width: 360px;
        margin-left: auto;
    }

    .badge.bg-white {
        background: rgba(129, 196, 8, 0.1) !important;
        color: #1a3a00;
        border: 1px solid rgba(129, 196, 8, 0.2);
    }

    .badge.bg-white i {
        color: var(--terms-sage);
    }

    .btn-outline-success {
        border-color: var(--terms-sage);
        color: var(--terms-sage);
        transition: all 0.3s ease;
    }

    .btn-outline-success:hover {
        background-color: var(--terms-sage);
        color: white;
        box-shadow: 0 10px 20px rgba(129, 196, 8, 0.3);
    }

    /* Mobile & Tablet layout fixes */
    @media (max-width: 991px) {

        .terms-header {
            padding: 55px 20px;
        }

        .legal-content {
            padding: 50px 15px;
            margin-top: -30px;
            border-radius: 35px 35px 0 0;
        }

        .terms-image-frame {
            max-width: 380px;
            margin: 30px auto 0;
            border-width: 8px;
        }

        .agreement-card {
            padding: 30px;
        }

        .highlight-box {
            padding: 35px 25px;
            border-radius: 28px;
        }

        h1.display-3 {
            font-size: 2.2rem;
        }

        .lead {
            font-size: 1rem;
        }
    }

    /* Small phones */
    @media (max-width: 576px) {

        .terms-header {
            padding: 45px 15px;
        }

        .legal-content {
            padding: 40px 12px;
        }

        .terms-image-frame {
            max-width: 300px;
            border-width: 6px;
        }

        .agreement-card {
            padding: 26px;
            border-radius: 22px;
        }

        .step-number {
            font-size: 2.4rem;
        }

        .highlight-box h2 {
            font-size: 1.4rem;
        }
    }

</style>

@include('user.pages.partials.page-header', [
    'title' => __('terms-of-use.title'),
    'breadcrumbs' => [
        [
            'label' => __('pages'),
            'icon'  => 'fas fa-layer-group',
            'url'   => url('/pages')
        ],
        [
            'label' => __('terms-of-use.title'),
            'icon'  => 'fas fa-file-signature'
        ]
    ]
])


{{-- 📜 Terms Hero Section --}}
<header class="terms-header">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 text-center text-lg-start">
                <span class="terms-label mb-3 d-block">{{ __('terms-of-use.label') }}</span>
                <h1 class="display-3 fw-bold" style="color: #1a3a00;">{!! __('terms-of-use.title') !!}</h1>
                <p class="lead text-muted mb-4">{{ __('terms-of-use.subtitle', ['app' => setting('app_name', 'EcoShop')]) }}</p>
                <div class="badge bg-white text-dark shadow-sm px-4 py-2 rounded-pill">
                    <i class="fas fa-file-signature me-2"></i> {{ __('terms-of-use.revised', ['date' => now()->format('M Y')]) }}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="terms-image-frame">
                    {{-- Image: High-end bamboo/wood textiles representing structure/fabrics --}}
                    <img src="{{ asset('storage/frontend/terms_of_use.jpg') }}" class="img-fluid" alt="Sustainable Textiles">
                </div>
            </div>
        </div>
    </div>
</header>

{{-- 📄 Main Agreement Content --}}
<section class="legal-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">

                <div class="row g-4">
                    {{-- Section 1: Responsibilities --}}
                    <div class="col-md-6">
                        <div class="agreement-card">
                            <div class="step-number">01</div>
                            <h3 class="fw-bold h4 mb-3" style="color: #1a3a00;">{{ __('terms-of-use.responsibilities_title') }}</h3>
                            <p class="text-muted small mb-4">{{ __('terms-of-use.responsibilities_desc') }}</p>
                            <ul class="list-unstyled list-eco">
                                @foreach(__('terms-of-use.responsibilities_items') as $item)
                                    <li><i class="fas fa-check-double"></i> {{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Section 2: Prohibited --}}
                    <div class="col-md-6">
                        <div class="agreement-card">
                            <div class="step-number">02</div>
                            <h3 class="fw-bold h4 mb-3" style="color: #1a3a00;">{{ __('terms-of-use.protection_title') }}</h3>
                            <p class="text-muted small mb-4">{{ __('terms-of-use.protection_desc') }}</p>
                            <ul class="list-unstyled list-eco">
                                @foreach(__('terms-of-use.protection_items') as $item)
                                    <li><i class="fas fa-times-circle text-danger"></i> {{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Liability (Full Width) --}}
                <div class="highlight-box">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-3">{{ __('terms-of-use.liability_title') }}</h2>
                            <p class="lead opacity-75">
                                {{ __('terms-of-use.liability_desc', ['app' => setting('app_name', 'EcoShop')]) }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end text-center">
                            <i class="fas fa-balance-scale-left" style="font-size: 5rem; opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>

                {{-- Bottom Action --}}
                <div class="text-center mt-5">
                    <p class="text-muted">{{ __('terms-of-use.footer_note') }}</p>
                    <a href="/" class="btn btn-outline-success rounded-pill px-5 py-2">{{ __('terms-of-use.back_btn') }}</a>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
