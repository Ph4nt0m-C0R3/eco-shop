<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Forgot Password | EcoShop</title>

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
        /* ===== Eco Overrides from Master ===== */
        :root {
            --eco-primary: #2e7d32;
            --eco-dark: #1b5e20;
            --eco-secondary: #81c784;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
        }

        .eco-card {
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            background: #fff;
        }

        .eco-section-title {
            font-weight: 600;
            color: var(--eco-dark);
        }

        .eco-save-btn {
            background: var(--eco-primary);
            border: none;
            border-radius: 2rem;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: 0.3s;
        }

        .eco-save-btn:hover {
            background: var(--eco-dark);
        }

        .form-floating .form-control {
            border-radius: 0.75rem;
            padding: 1rem 0.75rem;
        }

        /* ===== Fix Bootstrap 4 close button for Bootstrap 5 ===== */
        .alert-dismissible {
            position: relative;
            padding-right: 3rem;
        }

        .alert-dismissible .close {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            font-size: 1.2rem;
            line-height: 1;
            color: inherit;
            opacity: 0.6;
            cursor: pointer;
        }

        .alert-dismissible .close:hover {
            opacity: 1;
        }
    </style>
</head>

<body>

<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="eco-card p-4 p-md-5" style="max-width: 480px; width: 100%;">

        <!-- Header -->
        <div class="text-center mb-4">
            <i class="fas fa-unlock-alt text-success fs-2 mb-2"></i>
            <h4 class="eco-section-title mb-1">Forgot Password</h4>
            <p class="text-muted small mb-0">
                Enter your admin email to receive a reset link
            </p>
        </div>

        <!-- Alerts -->
        @include('components.partials.success-alert')
        @include('components.partials.error-alert')
        @include('components.partials.warning-alert')

        <!-- Form -->
        <form method="POST" action="{{ route('admin.password.email') }}">
            @csrf

            <div class="form-floating mb-4">
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control eco-input @error('email') is-invalid @enderror"
                    placeholder="Email Address"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
                <label for="email">Admin Email Address</label>

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn eco-save-btn w-100 text-white">
                <i class="fas fa-paper-plane me-1"></i>
                Send Reset Link
            </button>
        </form>

        <!-- Back to login -->
        <div class="text-center mt-4">
            <a href="{{ route('admin.login') }}" class="small text-success text-decoration-none">
                Back to login
            </a>
        </div>

    </div>
</div>

<!-- Footer -->
<p class="text-center text-muted small mt-4 mb-0">
    © {{ date('Y') }} EcoShop • Admin Access Only
</p>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('global/js/eco-common.js') }}"></script>

</body>
</html>
