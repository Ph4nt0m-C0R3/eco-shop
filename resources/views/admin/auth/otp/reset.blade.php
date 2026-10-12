<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | {{ config('app.brand') }}</title>

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
        input[type="password"]::-ms-reveal {
            display: none;
        }
    </style>
<link rel="stylesheet" href="{{ asset('vendor/ecoshop/578d676b9e7ee0.css') }}">
<script>window.ECO_SHOW_PASSWORD=@json(__('stability.show_password'));window.ECO_HIDE_PASSWORD=@json(__('stability.hide_password'));</script>
</head>

<body class="bg-light">

<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="eco-card p-4 p-md-5" style="max-width: 480px; width:100%;">

        <!-- Header -->
        <div class="text-center mb-4">
            <i class="fas fa-lock text-success fs-2 mb-2"></i>
            <h4 class="eco-section-title mb-1">Set New Password</h4>
            <p class="text-muted small mb-0">
                Choose a strong password for your admin account
            </p>
        </div>

        <!-- Alerts -->
        @include('components.partials.success-alert')
        @include('components.partials.error-alert')
        @include('components.partials.warning-alert')

        <!-- Form -->
        <p class="text-muted small">{{ __('auth-security.passphrase_hint') }}</p>
            <form method="POST" action="{{ route('admin.password.resetOtp') }}">
            @csrf

            <!-- New Password -->
            <div class="mb-3 text-start">
                <div class="form-floating password-wrapper">
                    <input
                        type="password"
                        name="password"
                        id="newPassword"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="New Password"
                    >
                    <label for="newPassword">New Password</label>

                    <button type="button" aria-label="{{ __('stability.show_password') }}" aria-pressed="false" class="fa-solid fa-eye-slash password-toggle"
                       onclick="togglePassword('newPassword', this)"></button>
                </div>

                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Confirm -->
            <div class="mb-4 text-start">
                <div class="form-floating password-wrapper">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="confirmPassword"
                        class="form-control"
                        placeholder="Confirm Password"
                    >
                    <label for="confirmPassword">Confirm Password</label>

                    <button type="button" aria-label="{{ __('stability.show_password') }}" aria-pressed="false" class="fa-solid fa-eye-slash password-toggle"
                       onclick="togglePassword('confirmPassword', this)"></button>
                </div>

                @error('password_confirmation')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button class="btn eco-save-btn w-100 text-white">
                <i class="fas fa-check me-1"></i>
                Update Password
            </button>
        </form>

    </div>
</div>

<script src="{{ asset('global/js/eco-common.js') }}"></script>
</body>
</html>
