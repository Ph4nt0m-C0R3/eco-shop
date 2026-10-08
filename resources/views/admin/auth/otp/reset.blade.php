<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | EcoShop</title>

    <!-- Favicon -->
    <link rel="icon"
    type="image/png"
    href="{{ setting('favicon')
        ? asset('storage/' . setting('favicon'))
        : asset('default/favicon.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Eco Common CSS -->
    <link rel="stylesheet" href="{{ asset('global/css/eco-common.css') }}">

    <style>
        input[type="password"]::-ms-reveal {
            display: none;
        }
    </style>
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

                    <i class="fa-solid fa-eye-slash password-toggle"
                       onclick="togglePassword('newPassword', this)"></i>
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

                    <i class="fa-solid fa-eye-slash password-toggle"
                       onclick="togglePassword('confirmPassword', this)"></i>
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
