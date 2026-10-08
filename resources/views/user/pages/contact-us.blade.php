@extends('user.layouts.master')

@section('content')

<style>
    :root {
        --c-dark: #1a3a00;
        --c-sage: #81c408;
        --c-cream: #f8fff0;
        --c-border: rgba(129, 196, 8, 0.2);
    }

    .contact-wrapper {
        background-color: var(--c-cream);
        padding-bottom: 100px;
    }

    .contact-header {
        padding: 100px 0 60px;
        text-align: center;
        border-bottom: 1px solid rgba(129, 196, 8, 0.1);
    }

    .contact-tagline {
        color: var(--c-sage);
        text-transform: uppercase;
        letter-spacing: 3px;
        font-weight: 700;
        font-size: 0.85rem;
        margin-bottom: 10px;
        display: block;
    }

    /* Info Side Cards */
    .info-item {
        background: #fff;
        border: 1px solid var(--c-border);
        border-radius: 25px;
        padding: 30px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        border-color: var(--c-sage);
        transform: translateX(10px);
        box-shadow: 0 10px 20px rgba(129, 196, 8, 0.1);
    }

    .info-icon {
        width: 45px;
        height: 45px;
        background: rgba(129, 196, 8, 0.1);
        color: var(--c-sage);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    /* Form Design */
    .form-card {
        background: #ffffff;
        border-radius: 40px;
        padding: 50px;
        box-shadow: 0 20px 50px rgba(129, 196, 8, 0.05);
        border: 1px solid var(--c-border);
        position: relative;
        overflow: hidden;
    }

    .form-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--c-sage), #689a06);
    }

    .contact-wrapper .form-control {
        background-color: rgba(129, 196, 8, 0.05);
        border: 1px solid transparent;
        border-radius: 15px;
        padding: 15px 20px;
    }

    .contact-wrapper .form-control:focus {
        background-color: #fff;
        border-color: var(--c-sage);
        box-shadow: 0 0 0 3px rgba(129, 196, 8, 0.1);
    }

    .btn-eco-send {
        background: linear-gradient(135deg, var(--c-sage), #689a06);
        color: #fff;
        border: none;
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-eco-send:hover {
        background: linear-gradient(135deg, #689a06, var(--c-sage));
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(129, 196, 8, 0.3);
    }

    .social-link {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(129, 196, 8, 0.1);
        color: var(--c-sage);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        transition: 0.3s;
        border: 1px solid rgba(129, 196, 8, 0.2);
    }

    .social-link:hover {
        background: var(--c-sage);
        color: #fff;
        transform: translateY(-3px);
    }

    .contact-wrapper .text-success {
        color: var(--c-sage) !important;
    }

    /* Mobile spacing fix */
    @media (max-width: 767.98px) {

        /* Header breathing room */
        .contact-header,
        .contact-wrapper .container {
            padding-left: 25px;
            padding-right: 25px;
        }

        /* Reduce tall header on small screens */
        .contact-header {
            padding: 70px 0 40px;
        }

        /* Info cards lighter on mobile */
        .info-item {
            padding: 20px;
            border-radius: 20px;
        }

        /* Form card spacing */
        .form-card {
            padding: 25px;
            border-radius: 25px;
        }

        /* Social box spacing */
        .contact-wrapper .mt-auto {
            padding: 20px !important;
        }

        /* Button spacing */
        .btn-eco-send.rounded-pill {
            padding-left: 25px !important;
            padding-right: 25px !important;
            border-radius: 25px;
        }
    }

</style>

@include('user.pages.partials.page-header',[
    'title' => __('contact.page_title'),
    'breadcrumbs' => [
        [
            'label' => __('contact.breadcrumb'),
            'icon'  => 'fas fa-envelope'
        ]
    ]
])

<div class="contact-wrapper">
    {{-- 🌿 Header --}}
    <header class="contact-header">
        <div class="container">
            <span class="contact-tagline">{{ __('contact.tagline') }}</span>
            <h1 class="display-3 fw-bold text-dark">
                {{ __('contact.heading_1') }}
                @if(__('contact.heading_2'))
                    <span class="text-success">{{ __('contact.heading_2') }}</span>
                @endif
            </h1>
            <p class="text-muted lead mx-auto" style="max-width: 600px;">
                {{ __('contact.description') }}
            </p>
        </div>
    </header>

    <div class="container">
        <div class="row g-5 align-items-stretch">

            {{-- 📍 Contact Info Sidebar --}}
            <div class="col-lg-5">
                <div class="d-flex flex-column h-100">

                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <h6 class="fw-bold mb-1" style="color: #1a3a00;">{{ __('contact.studio') }}</h6>
                        <p class="text-muted small mb-0">{{ setting('address', 'Hlaing Township, Yangon') }}</p>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-paper-plane"></i></div>
                        <h6 class="fw-bold mb-1" style="color: #1a3a00;">{{ __('contact.email_us') }}</h6>
                        <a href="mailto:{{ setting('contact_email') }}" class="text-decoration-none text-muted small">
                            {{ setting('contact_email', 'hello@example.test') }}
                        </a>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-phone"></i></div>
                        <h6 class="fw-bold mb-1" style="color: #1a3a00;">{{ __('contact.call_directly') }}</h6>
                        <p class="text-muted small mb-0">{{ setting('contact_phone', '000-000-0000') }}</p>
                    </div>

                    {{-- Social Presence --}}
                    <div class="mt-auto p-4">
                        <h6 class="fw-bold mb-3 small text-uppercase ls-1" style="color: #1a3a00;">{{ __('contact.follow') }}</h6>
                        <div class="d-flex">
                            <a href="#" class="social-link text-decoration-none"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-link text-decoration-none"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-link text-decoration-none"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ✉️ Contact Form --}}
            <div class="col-lg-7">
                <div class="form-card">
                    <h3 class="fw-bold mb-4" style="color: #1a3a00;">{{ __('contact.form_title') }}</h3>
                    <form method="POST" action="{{ route('contact.send') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">{{ __('contact.full_name') }}</label>
                                <input type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">{{ __('contact.email_address') }}</label>
                                <input type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">{{ __('contact.inquiry') }}</label>
                            <textarea name="message"
                                    rows="5"
                                    class="form-control">{{ old('message') }}</textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-eco-send rounded-pill">
                                {{ __('contact.send_btn') }} <i class="fas fa-paper-plane ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
