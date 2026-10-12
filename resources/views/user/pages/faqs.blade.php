@extends('user.layouts.master')

@section('content')

<style>
    :root {
        --faq-bg: #f8fff0;
        --faq-sage: #81c408;
        --faq-dark: #1a3a00;
        --faq-border: rgba(129, 196, 8, 0.2);
    }

    .faq-wrapper {
        background-color: var(--faq-bg);
        padding-bottom: 100px;
    }

    .faq-header {
        padding: 100px 0 60px;
        text-align: center;
        border-bottom: 1px solid rgba(129, 196, 8, 0.1);
    }

    .faq-label {
        color: var(--faq-sage);
        text-transform: uppercase;
        letter-spacing: 4px;
        font-weight: 700;
        font-size: 0.8rem;
        display: block;
        margin-bottom: 15px;
    }

    /* Custom Accordion Styling */
    .faq-accordion .accordion-item {
        background-color: #ffffff;
        border: 1px solid var(--faq-border) !important;
        border-radius: 20px !important;
        margin-bottom: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-accordion .accordion-item:hover {
        border-color: var(--faq-sage) !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(129, 196, 8, 0.1);
    }

    .faq-accordion .accordion-button {
        padding: 25px 30px;
        font-weight: 600;
        color: var(--faq-dark);
        background-color: #ffffff;
        box-shadow: none;
        border: none;
    }

    .faq-accordion .accordion-button:not(.collapsed) {
        color: var(--faq-sage);
        background-color: #ffffff;
        border-bottom: 1px solid var(--faq-border);
    }

    .faq-accordion .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%231a3a00'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .faq-accordion .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%2381c408'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .faq-accordion .accordion-body {
        padding: 30px;
        color: #666;
        line-height: 1.7;
        background-color: #f8fff0;
    }

    /* Help Banner */
    .help-banner {
        background: linear-gradient(135deg, var(--faq-dark), #2e7d32);
        border-radius: 30px;
        padding: 40px;
        margin-top: 60px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .help-banner::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--faq-sage);
    }

    .btn-outline-eco {
        border: 2px solid var(--faq-sage);
        color: var(--faq-sage);
        background: transparent;
        border-radius: 50px;
        padding: 10px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-eco:hover {
        background: var(--faq-sage);
        color: #fff;
        box-shadow: 0 10px 20px rgba(129, 196, 8, 0.3);
        transform: translateY(-2px);
    }

    .text-success {
        color: var(--faq-sage) !important;
    }

    /* Mobile spacing fix */
    @media (max-width: 767.98px) {

        /* Header breathing room */
        .faq-header,
        .faq-wrapper .container {
            padding-left: 25px;
            padding-right: 25px;
        }

        /* Reduce tall header on small screens */
        .faq-header {
            padding: 70px 0 40px;
        }

        /* Accordion cards lighter on mobile */
        .faq-accordion .accordion-item {
            border-radius: 18px !important;
        }

        .faq-accordion .accordion-button {
            padding: 18px 20px;
            font-size: 0.95rem;
        }

        .faq-accordion .accordion-body {
            padding: 20px;
        }

        /* Help banner spacing */
        .help-banner {
            padding: 25px;
            border-radius: 25px;
            text-align: center;
            flex-direction: column;
            gap: 20px;
        }

        /* Button spacing */
        .btn-outline-eco {
            padding-left: 25px;
            padding-right: 25px;
            border-radius: 25px;
        }
    }

</style>

@include('user.pages.partials.page-header', [
    'title' => __('faqs.title'),
    'breadcrumbs' => [
        [
            'label' => __('pages'),
            'icon'  => 'fas fa-layer-group',
            'url'   => url('/pages')
        ],
        [
            'label' => __('faqs.title'),
            'icon'  => 'fas fa-question-circle'
        ]
    ]
])

<div class="faq-wrapper">
    {{-- 🌿 Header --}}
    <header class="faq-header">
        <div class="container">
            <span class="faq-label">{{ __('faqs.label') }}</span>

            <h1 class="display-4 fw-bold mb-3" style="color: #1a3a00;">
                {!! __('faqs.heading') !!}
            </h1>

            <p class="text-muted mx-auto" style="max-width: 500px;">
                {{ __('faqs.subtitle') }}
            </p>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="accordion faq-accordion" id="faqAccordion">

                    {{-- Question 1 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                {{ __('faqs.q1') }}
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {!! __('faqs.a1') !!}
                            </div>
                        </div>
                    </div>

                    {{-- Question 2 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                {{ __('faqs.q2') }}
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {!! __('faqs.a2', ['url' => route('sales.refunds')]) !!}
                            </div>
                        </div>
                    </div>

                    {{-- Question 3 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                {{ __('faqs.q3') }}
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {{ __('faqs.a3') }}
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Help Banner --}}
                <div class="help-banner flex-column flex-md-row text-center text-md-start">
                    <div class="mb-4 mb-md-0">
                        <h4 class="fw-bold mb-1">{{ __('faqs.help_title') }}</h4>
                        <p class="mb-0 opacity-75">{{ __('faqs.help_desc') }}</p>
                    </div>

                    <a href="{{ route('contact.us') }}" class="btn btn-outline-eco">
                        {{ __('faqs.help_btn') }}
                        <i class="fas fa-comment-dots ms-2"></i>
                    </a>
                </div>

                <div class="text-center mt-5">
                    <p class="small text-muted">
                        <i class="fas fa-lock me-1" style="color: #81c408;"></i>
                        {{ __('faqs.footer_note') }}
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
