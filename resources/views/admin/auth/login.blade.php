<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | {{ config('app.brand') }}</title>

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
    <div class="form-wrapper">

        <!-- Eco Card -->
        <div class="eco-card p-4 p-md-5">

            <!-- Header -->
            <div class="text-center mb-4">
                <i class="fas fa-leaf text-success fs-2 mb-2"></i>
                <h4 class="eco-section-title mb-1">Admin Login</h4>
                <p class="text-muted small mb-0">{{ config('app.brand') }} Administration Panel</p>
            </div>

            @include('components.partials.success-alert')
            @include('components.partials.warning-alert')
            @include('components.partials.error-alert')
            @if(session('status'))<div class="alert alert-info" role="status">{{ session('status') }}</div>@endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login.store') }}">
                @csrf

                <!-- Email -->
                <div class="form-floating mb-3 text-start">
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Email Address"
                        value="{{ old('email') }}"
                    >
                    <label for="email">Email Address</label>

                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3 text-start">
                    <div class="form-floating password-wrapper">
                        <input
                            type="password"
                            name="password"
                            id="adminPassword"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Password"
                        >
                        <label for="adminPassword">Password</label>

                        <i
                            class="fa-solid fa-eye-slash fa-fw password-toggle"
                            onclick="togglePassword('adminPassword', this)">
                        </i>
                    </div>

                    @error('password')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Password recovery -->
                <div class="d-flex justify-content-end align-items-center mb-4">
                    <a href="{{ route('admin.password.request') }}"
                       class="small text-decoration-none text-success fw-semibold">
                        Forgot password?
                    </a>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn eco-save-btn w-100 text-white">
                    <i class="fas fa-lock me-1"></i>
                    Login
                </button>
            </form>

        </div>
        <!-- /Eco Card -->

        <!-- Footer -->
        <p class="text-center text-muted small mt-4 mb-0">
            © {{ date('Y') }} {{ config('app.brand') }} • Admin Access Only
        </p>

    </div>
</div>

<!-- Eco Common JS -->
<script src="{{ asset('global/js/eco-common.js') }}"></script>

</body>
</html>
