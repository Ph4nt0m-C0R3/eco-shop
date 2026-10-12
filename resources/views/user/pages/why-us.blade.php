@extends('user.layouts.master')

@section('content')

<style>
    :root {
        --v-dark: #1a3a00;
        --v-sage: #81c408;
        --v-cream: #f8fff0;
        --v-soft-green: rgba(129, 196, 8, 0.1);
    }

    .text-success {
        color: var(--v-sage) !important;
    }

    .values-wrapper {
        background-color: var(--v-cream);
        padding-bottom: 100px;
    }

    /* Elegant Text-Only Hero */
    .values-header {
        padding: 120px 0 80px;
        background: linear-gradient(to bottom, rgba(129, 196, 8, 0.05), var(--v-cream));
        border-bottom: 1px solid rgba(129, 196, 8, 0.1);
    }

    .values-header h1 {
        font-weight: 800;
        font-size: 3.5rem;
        color: var(--v-dark);
        margin-bottom: 15px;
    }

    .heart-pulse {
        color: var(--v-sage);
        animation: pulse 2s infinite;
        font-size: 2.5rem;
        margin-bottom: 20px;
        display: inline-block;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Grid Card Design */
    .feature-grid-card {
        background: #ffffff;
        border: 1px solid rgba(129, 196, 8, 0.1);
        border-radius: 30px;
        padding: 40px 30px;
        height: 100%;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }

    .feature-grid-card:hover {
        border-color: var(--v-sage);
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(129, 196, 8, 0.1);
    }

    .icon-circle-bg {
        width: 70px;
        height: 70px;
        background: var(--v-soft-green);
        color: var(--v-sage);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        font-size: 1.8rem;
        transition: 0.3s;
    }

    .feature-grid-card:hover .icon-circle-bg {
        background: var(--v-sage);
        color: #fff;
        transform: rotate(-10deg);
    }

    .feature-title {
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 15px;
        color: var(--v-dark);
    }

    .feature-desc {
        font-size: 0.95rem;
        color: #6c757d;
        line-height: 1.6;
    }

    /* Bottom Trust Bar */
    .trust-bar {
        background: linear-gradient(135deg, var(--v-dark), #2e7d32);
        color: #fff;
        border-radius: 100px;
        padding: 20px 40px;
        margin-top: 80px;
        display: inline-flex;
        align-items: center;
        gap: 20px;
        position: relative;
        overflow: hidden;
    }

    .trust-bar::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--v-sage);
    }

    .trust-bar .text-success {
        color: var(--v-sage) !important;
    }

    /* Mobile spacing fix */
    @media (max-width: 767.98px) {

        /* Header breathing room */
        .values-header,
        .values-wrapper .container {
            padding-left: 25px;
            padding-right: 25px;
        }

        /* Reduce tall hero on mobile */
        .values-header {
            padding: 80px 0 50px;
        }

        .values-header h1 {
            font-size: 2.2rem;
        }

        .heart-pulse {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        /* Feature cards lighter on mobile */
        .feature-grid-card {
            padding: 25px 20px;
            border-radius: 22px;
        }

        .icon-circle-bg {
            width: 55px;
            height: 55px;
            font-size: 1.4rem;
            margin-bottom: 18px;
        }

        .feature-title {
            font-size: 1.1rem;
        }

        /* Trust bar spacing */
        .trust-bar {
            padding: 15px 25px;
            border-radius: 40px;
            flex-wrap: wrap;
            justify-content: center;
            text-align: center;
            gap: 15px;
        }
    }

</style>

@include('user.pages.partials.page-header', [
    'title' => __('why-us.title'),
    'breadcrumbs' => [
        [
            'label' => __('pages'),
            'icon'  => 'fas fa-layer-group',
            'url'   => url('/pages')
        ],
        [
            'label' => __('why-us.title'),
            'icon'  => 'fas fa-heart'
        ]
    ]
])

<div class="values-wrapper">
    {{-- 🌿 Hero Section --}}
    <header class="values-header text-center">
        <div class="container">
            <div class="heart-pulse"><i class="fas fa-heart"></i></div>
            <h1 class="display-3" style="color: #1a3a00;">{!! __('why-us.title') !!}</h1>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">
                {{ __('why-us.subtitle', ['app' => setting('app_name', 'EcoShop')]) }}
            </p>
        </div>
    </header>

    <div class="container">
        <div class="row g-4">

            @php
            $icons = [
                'fa-leaf',
                'fa-shipping-fast',
                'fa-user-shield',
                'fa-seedling',
                'fa-hand-holding-heart',
                'fa-award',
            ];
            @endphp

            @foreach(__('why-us.features') as $index => $feature)
            <div class="col-md-6 col-lg-4">
                <div class="feature-grid-card text-center">
                    <div class="icon-circle-bg">
                        <i class="fas {{ $icons[$index] }}"></i>
                    </div>
                    <h5 class="feature-title">{{ $feature['title'] }}</h5>
                    <p class="feature-desc">{{ $feature['desc'] }}</p>
                </div>
            </div>
            @endforeach

        </div>

        {{-- Final Trust Indicator --}}
        <div class="text-center">
            <div class="trust-bar shadow">
                <i class="fas fa-shield-check text-success"></i>
                <span class="small fw-bold">{{ __('why-us.trust_1') }}</span>
                <span class="opacity-25">|</span>
                <span class="small fw-bold">{{ __('why-us.trust_2') }}</span>
            </div>
        </div>
    </div>
</div>

@endsection
