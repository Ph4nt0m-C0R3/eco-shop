<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Admin Password | {{ config('app.brand') }}</title>

    <!-- Favicon -->
        <link rel="icon"
        type="image/png"
        href="{{ setting('favicon')
            ? asset('storage/' . setting('favicon'))
            : asset('storage/default/favicon.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="{{ asset('vendor/ecoshop/f7456a80eeff00.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="{{ asset('vendor/ecoshop/dbe397d0d04e2b.css') }}" rel="stylesheet">

    <!-- Eco Common CSS -->
    <link rel="stylesheet" href="{{ asset('global/css/eco-common.css') }}">

    <style>
        body {
            background: #f6faf7;
        }

        input[type="password"]::-ms-reveal {
            display: none;
        }

        /* MATCH REFERENCE PASSWORD INPUT BEHAVIOR */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 3rem;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            cursor: pointer;
            color: #888;
            width: 24px;
            text-align: center;
            line-height: 1;
        }

        .form-floating > label {
            padding-right: 3rem;
        }
    </style>
<link rel="stylesheet" href="{{ asset('vendor/ecoshop/578d676b9e7ee0.css') }}">
<script>window.ECO_SHOW_PASSWORD=@json(__('stability.show_password'));window.ECO_HIDE_PASSWORD=@json(__('stability.hide_password'));</script>
</head>

<body>

<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="form-wrapper">

        <div class="eco-card p-4 p-md-5">

            <!-- Header -->
            <div class="text-center mb-4">
                <i class="fas fa-lock text-success fs-2 mb-2"></i>
                <h4 class="eco-section-title mb-1">Reset Password</h4>
                <p class="text-muted small mb-0">
                    Create a new password for your admin account
                </p>
            </div>

            <!-- Alerts -->
            @include('components.partials.success-alert')
            @include('components.partials.error-alert')
            @include('components.partials.warning-alert')

            <!-- Form -->
            <p class="text-muted small">{{ __('auth-security.passphrase_hint') }}</p>
            <form method="POST" action="{{ route('admin.password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- New Password -->
                <div class="mb-3 text-start">
                    <div class="form-floating password-wrapper">
                        <input
                            type="password"
                            name="password"
                            id="resetPassword"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="New Password"
                        >
                        <label for="resetPassword">New Password</label>

                        <button type="button" aria-label="{{ __('stability.show_password') }}" aria-pressed="false"
                            class="fa-solid fa-eye-slash fa-fw password-toggle"
                            onclick="togglePassword('resetPassword', this)"
                        ></button>
                    </div>

                    @error('password')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4 text-start">
                    <div class="form-floating password-wrapper">
                        <input
                            type="password"
                            name="password_confirmation"
                            id="resetPasswordConfirm"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            placeholder="Confirm Password"
                        >
                        <label for="resetPasswordConfirm">Confirm Password</label>

                        <button type="button" aria-label="{{ __('stability.show_password') }}" aria-pressed="false"
                            class="fa-solid fa-eye-slash fa-fw password-toggle"
                            onclick="togglePassword('resetPasswordConfirm', this)"
                        ></button>
                    </div>

                    @error('password_confirmation')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="btn eco-save-btn w-100 text-white">
                    <i class="fas fa-check-circle me-1"></i>
                    Reset Password
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('admin.login') }}" class="text-success small fw-semibold">
                    Back to login
                </a>
            </div>

        </div>
    </div>
</div>

<!-- Eco Common JS -->
<script src="{{ asset('global/js/eco-common.js') }}"></script>

</body>
</html>
