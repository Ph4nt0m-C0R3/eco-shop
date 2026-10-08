@extends('user.layouts.master')

@section('content')

<style>
    :root {
        --eco-dark: #1a3a00;
        --eco-sage: #81c408;
        --eco-cream: #f8fff0;
        --eco-accent: #ff9800;
    }

    .text-success {
        color: var(--eco-sage) !important;
    }

    .privacy-header {
        background-color: var(--eco-cream);
        padding: 80px 0;
        border-bottom: 1px solid rgba(129, 196, 8, 0.1);
    }

    .policy-image-wrap img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
    }

    .eco-label {
        color: var(--eco-sage);
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .policy-image-wrap {
        position: relative;
        border-radius: 40px 0 40px 0;
        overflow: hidden;
        box-shadow: 20px 20px 60px rgba(129, 196, 8, 0.1);
        border: 1px solid rgba(129, 196, 8, 0.1);
    }

    .content-section {
        background: #fff;
        margin-top: -50px;
        border-radius: 40px 40px 0 0;
        padding: 60px 0;
    }

    .info-card {
        background: var(--eco-cream);
        border: 1px solid rgba(129, 196, 8, 0.1);
        border-radius: 24px;
        padding: 40px;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(129, 196, 8, 0.15);
    }

    .icon-circle {
        width: 60px;
        height: 60px;
        background: rgba(129, 196, 8, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
        color: var(--eco-sage);
        font-size: 1.5rem;
        box-shadow: 0 10px 20px rgba(129, 196, 8, 0.05);
    }

    .contact-banner {
        background: linear-gradient(135deg, var(--eco-sage), #689a06);
        border-radius: 30px;
        padding: 50px;
        color: #fff;
        margin-top: 60px;
        position: relative;
        overflow: hidden;
    }

    .contact-banner::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--eco-accent);
    }

    .btn-eco {
        background: var(--eco-sage);
        color: white;
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
    }

    .btn-eco:hover {
        background: #689a06;
        color: white;
        box-shadow: 0 10px 20px rgba(129, 196, 8, 0.3);
        transform: translateY(-2px);
    }

    /* Accent colors for list items */
    .list-unstyled .fa-check {
        color: var(--eco-sage);
    }

    .list-unstyled .fa-arrow-right {
        color: var(--eco-accent);
    }

    /* Mobile & Tablet spacing improvements */
    @media (max-width: 991px) {

        .privacy-header {
            padding: 50px 20px;
        }

        .content-section {
            padding: 40px 15px;
            margin-top: -30px;
            border-radius: 30px 30px 0 0;
        }

        .policy-image-wrap {
            margin-top: 30px;
            border-radius: 25px;
        }

        .info-card {
            padding: 25px;
        }

        .contact-banner {
            padding: 30px 20px;
            border-radius: 22px;
        }

        h1.display-3 {
            font-size: 2.2rem;
        }

        h2 {
            font-size: 1.6rem;
        }

        .lead {
            font-size: 1rem;
        }
    }

    /* Small phones */
    @media (max-width: 576px) {

        .privacy-header {
            padding: 50px 35px;
        }

        .content-section {
            padding: 35px 12px;
        }

        .info-card {
            padding: 22px;
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }

        .contact-banner h3 {
            font-size: 1.4rem;
        }
    }

</style>

@include('user.pages.partials.page-header', [
    'title' => __('privacy-policy.title'),
    'breadcrumbs' => [
        [
            'label' => __('pages'),
            'icon'  => 'fas fa-layer-group',
            'url'   => url('/pages')
        ],
        [
            'label' => __('privacy-policy.title'),
            'icon'  => 'fas fa-user-shield'
        ]
    ]
])

{{-- 🌿 Modern Hero Section --}}
<header class="privacy-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <span class="eco-label mb-2 d-block">{{ __('privacy-policy.label') }}</span>
                <h1 class="display-3 fw-bold mb-4" style="color: #1a3a00;">
                    {!! __('privacy-policy.title') !!}
                </h1>
                <p class="lead text-muted mb-4">{{ __('privacy-policy.subtitle') }}</p>
                <div class="d-flex align-items-center text-muted">
                    <i class="far fa-calendar-alt me-2" style="color: #81c408;"></i>
                    <span>{{ __('privacy-policy.effective', ['date' => now()->format('M d, Y')]) }}</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="policy-image-wrap">
                    {{-- Using an image of reusable glass jars/sustainable storage --}}
                    <img src="{{ asset('storage/frontend/privacy_policy.jpg') }}" class="img-fluid" alt="Sustainable Storage">
                </div>
            </div>
        </div>
    </div>
</header>

{{-- 📄 Page Content --}}
<section class="content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="mb-5 text-center">
                    <h2 class="fw-bold" style="color: #1a3a00;">{{ __('privacy-policy.section_title') }}</h2>
                    <p class="text-muted">{!! __('privacy-policy.section_subtitle', ['app' => setting('app_name', 'EcoShop')]) !!}</p>
                </div>

                <div class="row g-4">
                    {{-- Card 1 --}}
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="icon-circle">
                                <i class="fas fa-fingerprint"></i>
                            </div>
                            <h4 class="fw-bold" style="color: #1a3a00;">{{ __('privacy-policy.collect_title') }}</h4>
                            <p class="text-muted small mb-4">{{ __('privacy-policy.collect_desc') }}</p>
                            <ul class="list-unstyled">
                            @foreach(__('privacy-policy.collect_items') as $item)
                                <li class="mb-3">
                                    <i class="fas fa-check me-2"></i> {{ $item }}
                                </li>
                            @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Card 2 --}}
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="icon-circle">
                                <i class="fas fa-recycle"></i>
                            </div>
                            <h4 class="fw-bold" style="color: #1a3a00;">{{ __('privacy-policy.use_title') }}</h4>
                            <p class="text-muted small mb-4">{{ __('privacy-policy.use_desc') }}</p>
                            <ul class="list-unstyled">
                            @foreach(__('privacy-policy.use_items') as $item)
                                <li class="mb-3">
                                    <i class="fas fa-arrow-right me-2"></i> {{ $item }}
                                </li>
                            @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Contact Banner --}}
                <div class="contact-banner text-center position-relative overflow-hidden">
                    {{-- Subtle background decoration --}}
                    <i class="fas fa-leaf position-absolute" style="font-size: 200px; color: rgba(255,255,255,0.1); right: -50px; bottom: -50px;"></i>

                    <h3 class="fw-bold mb-3">{{ __('privacy-policy.contact_title') }}</h3>
                    <p class="opacity-75 mb-4">{{ __('privacy-policy.contact_desc') }}</p>
                    <a href="mailto:{{ setting('contact_email') }}" class="btn btn-eco btn-lg">
                        <i class="fas fa-envelope me-2"></i> {{ __('privacy-policy.contact_btn') }}
                    </a>
                </div>

                <div class="mt-5 text-center text-muted small">
                    <p><i class="fas fa-info-circle me-1" style="color: #81c408;"></i> {{ __('privacy-policy.footer_note') }}</p>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
