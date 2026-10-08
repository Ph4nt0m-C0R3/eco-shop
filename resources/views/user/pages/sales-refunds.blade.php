@extends('user.layouts.master')

@section('content')

<style>
    :root {
        --refund-dark: #1a3a00;
        --refund-sage: #81c408;
        --refund-clay: #ff9800;
        --refund-paper: #f8fff0;
    }

    .refund-hero {
        background: var(--refund-paper);
        padding: 100px 0;
        border-bottom: 1px solid rgba(129, 196, 8, 0.1);
    }

    .policy-visual-box {
        position: relative;
        padding: 20px;
    }

    .policy-visual-box img {
        border-radius: 30px;
        box-shadow: 30px 30px 0px rgba(129, 196, 8, 0.15);
        border: 1px solid rgba(129, 196, 8, 0.1);
    }

    .timeline-container {
        position: relative;
        padding: 40px 0;
    }

    .timeline-item {
        background: #fff;
        border-radius: 25px;
        padding: 30px;
        margin-bottom: 30px;
        border-left: 6px solid var(--refund-sage);
        box-shadow: 0 10px 30px rgba(129, 196, 8, 0.05);
        transition: all 0.3s ease;
    }

    .timeline-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(129, 196, 8, 0.1);
    }

    .days-badge {
        display: inline-block;
        background: var(--refund-sage);
        color: #fff;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
        margin-bottom: 10px;
        box-shadow: 0 4px 10px rgba(129, 196, 8, 0.2);
    }

    .perishables-warning {
        background: rgba(255, 152, 0, 0.05);
        border: 2px dashed var(--refund-clay);
        border-radius: 30px;
        padding: 40px;
    }

    .eco-icon-lg {
        font-size: 2.5rem;
        color: var(--refund-sage);
        margin-bottom: 20px;
    }

    .text-success {
        color: var(--refund-sage) !important;
    }

    .refund-wrapper .rounded-pill {
        background: rgba(129, 196, 8, 0.1);
        border: 1px solid rgba(129, 196, 8, 0.2);
    }

    .text-danger {
        color: var(--refund-clay) !important;
    }

    /* Mobile spacing fix */
    @media (max-width: 767.98px) {

        /* Add horizontal breathing room */
        .refund-hero,
        .timeline-container,
        .perishables-warning,
        section.py-5 {
            padding-left: 30px;
            padding-right: 30px;
        }

        /* Cards shouldn't touch screen edges */
        .timeline-item {
            padding: 20px;
            margin-left: 5px;
            margin-right: 5px;
        }

        /* Reduce heavy image shadow on mobile */
        .policy-visual-box img {
            box-shadow: 15px 15px 0px rgba(129, 196, 8, 0.1);
        }

        /* Footer pill spacing */
        .refund-wrapper .rounded-pill {
            padding-left: 20px;
            padding-right: 20px;
            border-radius: 25px;
        }
    }

</style>

@include('user.pages.partials.page-header', [
    'title' => __('sales-refunds.title'),
    'breadcrumbs' => [
        [
            'label' => __('pages'),
            'icon'  => 'fas fa-layer-group',
            'url'   => url('/pages')
        ],
        [
            'label' => __('sales-refunds.title'),
            'icon'  => 'fas fa-undo-alt'
        ]
    ]
])

{{-- 📦 Policy Hero --}}
<section class="refund-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h4 class="text-success text-uppercase fw-bold ls-2 mb-3" style="letter-spacing: 2px;">{{ __('sales-refunds.label') }}</h4>
                <h1 class="display-4 fw-bold mb-4" style="color: #1a3a00;">{!! __('sales-refunds.title') !!}</h1>
                <p class="lead text-muted">{{ __('sales-refunds.subtitle') }}</p>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="policy-visual-box">
                    {{-- Image: Sustainable brown paper packaging / shipping box --}}
                    <img src="{{ asset('storage/frontend/sales_refunds.jpg') }}" class="img-fluid" alt="Eco Shipping">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 🔄 Policy Flow --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="text-center mb-5">
                    <h2 class="fw-bold" style="color: #1a3a00;">{{ __('sales-refunds.flow_title') }}</h2>
                    <p class="text-muted">{{ __('sales-refunds.flow_subtitle') }}</p>
                </div>

                <div class="timeline-container">
                    <div class="row">
                        {{-- Processing --}}
                        <div class="col-md-4">
                            <div class="timeline-item">
                                <span class="days-badge">{{ __('sales-refunds.processing_days') }}</span>
                                <i class="fas fa-box-open d-block eco-icon-lg"></i>
                                <h5 class="fw-bold" style="color: #1a3a00;">{{ __('sales-refunds.processing_title') }}</h5>
                                <p class="small text-muted mb-0">{{ __('sales-refunds.processing_desc') }}</p>
                            </div>
                        </div>

                        {{-- Returns --}}
                        <div class="col-md-4">
                            <div class="timeline-item">
                                <span class="days-badge">{{ __('sales-refunds.returns_days') }}</span>
                                <i class="fas fa-undo-alt d-block eco-icon-lg"></i>
                                <h5 class="fw-bold" style="color: #1a3a00;">{{ __('sales-refunds.returns_title') }}</h5>
                                <p class="small text-muted mb-0">{{ __('sales-refunds.returns_desc') }}</p>
                            </div>
                        </div>

                        {{-- Refunds --}}
                        <div class="col-md-4">
                            <div class="timeline-item">
                                <span class="days-badge">{{ __('sales-refunds.refund_days') }}</span>
                                <i class="fas fa-hand-holding-usd d-block eco-icon-lg"></i>
                                <h5 class="fw-bold" style="color: #1a3a00;">{{ __('sales-refunds.refund_title') }}</h5>
                                <p class="small text-muted mb-0">{{ __('sales-refunds.refund_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Non-Refundable Section --}}
                <div class="perishables-warning mt-3">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #ff9800;"></i>
                        </div>
                        <div class="col-md-6">
                            <h4 class="fw-bold mb-1" style="color: #1a3a00;">{{ __('sales-refunds.non_refundable_title') }}</h4>
                            <p class="text-muted mb-0">{{ __('sales-refunds.non_refundable_desc') }}</p>
                        </div>
                        <div class="col-md-4">
                            <ul class="list-unstyled mb-0 mt-3 mt-md-0">
                                @foreach(__('sales-refunds.non_refundable_items') as $item)
                                    <li><i class="fas fa-times me-2" style="color: #ff9800;"></i> {{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Support Footer --}}
                <div class="text-center mt-5 p-5 rounded-pill" style="background: var(--refund-paper);">
                    <p class="mb-0">{{ __('sales-refunds.support_text') }}
                        <a href="mailto:{{ setting('contact_email') }}" class="fw-bold text-decoration-none ms-2" style="color: #81c408;">
                            {{ __('sales-refunds.support_link') }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
