<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Identity | {{ config('app.brand') }}</title>

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
<link rel="stylesheet" href="{{ asset('vendor/ecoshop/578d676b9e7ee0.css') }}">
<script>window.ECO_SHOW_PASSWORD=@json(__('stability.show_password'));window.ECO_HIDE_PASSWORD=@json(__('stability.hide_password'));</script>
</head>

<body class="bg-light">

<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="eco-card p-4 p-md-5" style="max-width: 480px; width:100%;">

        <!-- Header -->
        <div class="text-center mb-4">
            <i class="fas fa-shield-alt text-success fs-2 mb-2"></i>
            <h4 class="eco-section-title mb-1">Verify Your Identity</h4>
            <p class="text-muted small mb-0">
                We’ll send a one-time code to your admin email
            </p>
        </div>

        <!-- Alerts -->
        @include('components.partials.success-alert')
        @include('components.partials.error-alert')
        @include('components.partials.warning-alert')

        <!-- Form -->
        <form method="POST" action="{{ route('admin.password.sendOtp') }}">
            @csrf

            <div class="form-floating mb-4">
                <input
                    type="email"
                    class="form-control"
                    value="{{ auth()->user()->email }}"
                    disabled
                >
                <label>Email Address</label>
            </div>

            <button class="btn eco-save-btn w-100 text-white">
                <i class="fas fa-paper-plane me-1"></i>
                Send OTP
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ redirect()->back()->getTargetUrl() }}"
               class="small text-success fw-semibold text-decoration-none">
                Cancel & go back
            </a>
        </div>

    </div>
</div>

<script src="{{ asset('global/js/eco-common.js') }}"></script>
</body>
</html>
