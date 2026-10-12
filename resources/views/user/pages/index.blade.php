@extends('user.layouts.master')

@section('content')

<style>
    :root {
        --pages-bg: #f8fff0;
        --pages-sage: #81c408;
        --pages-dark: #1a3a00;
        --pages-border: rgba(129,196,8,0.15);
    }

    .pages-wrapper {
        background: var(--pages-bg);
        padding-bottom: 100px;
    }

    .pages-header {
        padding: 100px 0 60px;
        text-align: center;
        border-bottom: 1px solid var(--pages-border);
    }

    .pages-label {
        color: var(--pages-sage);
        text-transform: uppercase;
        letter-spacing: 4px;
        font-weight: 700;
        font-size: 0.8rem;
    }

    /* Card grid */
    .page-card {
        background: #fff;
        border: 1px solid var(--pages-border);
        border-radius: 30px;
        padding: 40px;
        height: 100%;
        text-align: center;
        transition: all .3s ease;
    }

    .page-card:hover {
        transform: translateY(-8px);
        border-color: var(--pages-sage);
        box-shadow: 0 20px 50px rgba(129,196,8,0.1);
    }

    .icon-box {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        margin: 0 auto 20px;
        background: rgba(129,196,8,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--pages-sage);
        font-size: 1.6rem;
    }

    .page-card h5 {
        font-weight: 700;
        color: var(--pages-dark);
    }

    .page-card p {
        color: #666;
        font-size: 0.95rem;
    }

    .btn-view {
        border: 2px solid var(--pages-sage);
        color: var(--pages-sage);
        border-radius: 50px;
        padding: 8px 25px;
        font-weight: 600;
        transition: .3s;
    }

    .btn-view:hover {
        background: var(--pages-sage);
        color: #fff;
    }

    .text-success {
        color: var(--pages-sage) !important;
    }
</style>

@include('user.pages.partials.page-header', [
    'title' => __('pages.title'),
    'breadcrumbs' => [
        [
            'label' => __('pages.title'),
            'icon' => 'fas fa-layer-group'
        ]
    ]
])

<div class="pages-wrapper">

    {{-- 🌿 Header --}}
    <header class="pages-header">
        <div class="container">
            <span class="pages-label">{{ __('pages.label') }}</span>
            <h1 class="fw-bold">{!! __('pages.heading') !!}</h1>
            <p class="text-muted">{{ __('pages.subtitle') }}</p>
        </div>
    </header>

    <div class="container mt-5">
        <div class="row g-4">

            {{-- About Us --}}
            <div class="col-md-4">
                <div class="page-card">
                    <div class="icon-box">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h5>{{ __('pages.about_title') }}</h5>
                    <p>{{ __('pages.about_desc') }}</p>
                    <a href="{{ route('about.us') }}" class="btn btn-view mt-3">{{ __('pages.about_btn') }}</a>
                </div>
            </div>

            {{-- Why Us --}}
            <div class="col-md-4">
                <div class="page-card">
                    <div class="icon-box">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h5>{{ __('pages.why_title') }}</h5>
                    <p>{{ __('pages.why_desc') }}</p>
                    <a href="{{ route('why.us') }}" class="btn btn-view mt-3">{{ __('pages.why_btn') }}</a>
                </div>
            </div>

            {{-- FAQs --}}
            <div class="col-md-4">
                <div class="page-card">
                    <div class="icon-box">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h5>{{ __('pages.faq_title') }}</h5>
                    <p>{{ __('pages.faq_desc') }}</p>
                    <a href="{{ route('faqs') }}" class="btn btn-view mt-3">{{ __('pages.faq_btn') }}</a>
                </div>
            </div>

            {{-- Privacy --}}
            <div class="col-md-4">
                <div class="page-card">
                    <div class="icon-box">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h5>{{ __('pages.privacy_title') }}</h5>
                    <p>{{ __('pages.privacy_desc') }}</p>
                    <a href="{{ route('privacy.policy') }}" class="btn btn-view mt-3">{{ __('pages.privacy_btn') }}</a>
                </div>
            </div>

            {{-- Terms --}}
            <div class="col-md-4">
                <div class="page-card">
                    <div class="icon-box">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <h5>{{ __('pages.terms_title') }}</h5>
                    <p>{{ __('pages.terms_desc') }}</p>
                    <a href="{{ route('terms.use') }}" class="btn btn-view mt-3">{{ __('pages.terms_btn') }}</a>
                </div>
            </div>

            {{-- Sales & Refunds --}}
            <div class="col-md-4">
                <div class="page-card">
                    <div class="icon-box">
                        <i class="fas fa-rotate-left"></i>
                    </div>
                    <h5>{{ __('pages.sales_title') }}</h5>
                    <p>{{ __('pages.sales_desc') }}</p>
                    <a href="{{ route('sales.refunds') }}" class="btn btn-view mt-3">{{ __('pages.sales_btn') }}</a>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
