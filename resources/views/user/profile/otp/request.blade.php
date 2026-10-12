@extends('user.layouts.master')

@section('content')

@include('user.pages.partials.page-header', [
    'title' => __('security.identity_check'),
    'breadcrumbs' => [
        [
            'label' => __('profile.title'),
            'url'   => route('user.profile'),
            'icon'  => 'fas fa-id-card'
        ],
        [
            'label' => __('security.identity_check'),
            'icon'  => 'fas fa-user-shield'
        ]
    ]
])

<div class="container-fluid my-5">
    <div class="container d-flex justify-content-center">
        <div class="eco-card border-0 shadow-lg p-4 p-md-5" style="max-width: 500px; width: 100%; border-radius: 20px; background: #fff;">

            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 100px; height: 100px;">
                    <i class="fas fa-user-shield text-primary" style="font-size: 3rem;"></i>
                </div>
                <h3 class="fw-bold text-dark">{{ __('security.identity_check') }}</h3>
                <p class="text-muted small">
                    {{ __('security.otp_desc') }}
                    <span class="text-primary fw-bold">{{ __('security.otp') }}</span>
                </p>
            </div>

            @include('components.partials.success-alert')
            @include('components.partials.error-alert')

            <form method="POST" action="{{ route('user.password.sendOtp') }}">
                @csrf

                <div class="mb-4">
                    <div class="form-floating eco-input-wrapper">
                        <input type="email"
                               class="form-control bg-light border-0 shadow-none"
                               id="emailInput"
                               value="{{ auth()->user()->email }}"
                               placeholder="name@example.com"
                               readonly>
                        <label for="emailInput">{{ __('security.verified_email') }}</label>
                        <i class="fas fa-envelope eco-input-icon"></i>
                    </div>
                    <div class="d-flex align-items-center mt-2 px-1">
                        <i class="fas fa-info-circle text-primary me-2" style="font-size: 0.85rem;"></i>
                        <small class="text-muted">{{ __('security.otp_expire') }}</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary border-0 w-100 py-3 rounded-pill text-white fw-bold shadow-sm hover-lift">
                    <i class="fas fa-paper-plane me-2"></i> {{ __('security.send_code') }}
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('user.profile') }}" class="text-secondary text-decoration-none small fw-bold hover-opacity">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('security.back_profile') }}
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .page-header { margin-top: 90px; }

    /* Floating Icon Logic */
    .eco-input-wrapper {
        position: relative;
    }

    .eco-input-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 1.1rem;
        z-index: 10;
        pointer-events: none; /* Icon won't block clicks on input */
    }

    /* Style for Floating Labels */
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: #81c408; /* Eco Green */
    }

    /* Highlight icon when field is focused */
    .form-floating > .form-control:focus ~ .eco-input-icon {
        color: #81c408;
    }

    .eco-card { transition: all 0.3s ease; }
    .hover-lift:hover { transform: translateY(-3px); filter: brightness(1.05); }
    .hover-opacity:hover { opacity: 0.8; }

    .form-floating .form-control {
        height: auto !important;
        padding-top: 1.75rem !important;
        padding-bottom: 0.5rem !important;
        line-height: 2;
    }
</style>

@endsection
