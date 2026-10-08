<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EcoShop | Forgot Password</title>

    <!-- Favicon -->
        <link rel="icon"
        type="image/png"
        href="{{ setting('favicon')
            ? asset('storage/' . setting('favicon'))
            : asset('default/favicon.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(160deg, #e8f8f1, #b7e4c7, #74c69d);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        input[type="password"]::-ms-reveal {
            display: none;
        }

        /* ===== ECO CARD ===== */
        .eco-reset-card {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            border-radius: 26px;
            padding: 50px 45px;
            max-width: 460px;
            width: 100%;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
            animation: fadeUp 0.9s ease forwards;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .eco-icon {
            width: 90px;
            height: 90px;
            background: #ffffff;
            border-radius: 50%;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            animation: floatIcon 4s ease-in-out infinite;
        }

        @keyframes floatIcon {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .eco-icon i {
            font-size: 38px;
            color: #27ae60;
        }

        .form-control {
            border-radius: 14px;
            padding: 14px 16px;
        }

        .form-control:focus {
            border-color: #27ae60;
            box-shadow: 0 0 15px rgba(39,174,96,0.35);
        }

        .btn-eco {
            background: #27ae60;
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-weight: 600;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .btn-eco:hover {
            background: #1e8449;
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(39,174,96,0.4);
        }

        .btn-eco:active {
            transform: scale(0.97);
        }

        .back-link {
            text-decoration: none;
            font-weight: 500;
            color: #2d6a4f;
        }

        .back-link:hover {
            color: #1e8449;
        }

        /* ===== FLOATING INPUT ===== */
        .form-floating .form-control {
            border-radius: 14px;
            padding: 1.2rem 1rem;
        }

        .form-floating label {
            padding-left: 1rem;
            color: #6c757d;
        }

        .form-floating .form-control:focus {
            border-color: #27ae60;
            box-shadow: 0 0 15px rgba(39,174,96,0.35);
        }

        /* ===== SUCCESS NOTICE ===== */
        .eco-success {
            background: linear-gradient(135deg, #d1fae5, #ecfdf5);
            border-radius: 16px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            color: #065f46;
            box-shadow: 0 10px 30px rgba(39,174,96,0.25);
            animation: popIn 0.6s ease forwards;
        }

        .eco-success i {
            font-size: 26px;
            color: #27ae60;
        }

        @keyframes popIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* ===== REDESIGNED ECO HINT ===== */
        .eco-hint {
            position: relative;
            background: #ffffff;
            border-radius: 16px;
            padding: 16px 18px;
            box-shadow: 0 12px 28px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 0.9rem;
            color: #2d6a4f;
        }

        .eco-hint::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 16px;
            padding: 2px;
            background: linear-gradient(135deg, #27ae60, #74c69d);
            -webkit-mask:
                linear-gradient(#fff 0 0) content-box,
                linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }

        .eco-hint i {
            font-size: 22px;
            color: #27ae60;
        }

        .eco-success {
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .eco-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 40px rgba(39,174,96,0.35);
        }

        /* Remove Bootstrap invalid icons, keep red border */
        .form-control.is-invalid {
            background-image: none !important;
            padding-right: 1rem; /* reset spacing */
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.15rem rgba(220, 53, 69, 0.15);
        }

    </style>
</head>

<body>

<div class="eco-reset-card text-center">

    <!-- Icon -->
    <div class="eco-icon">
        <i class="fas fa-seedling"></i>
    </div>

    <h2 class="fw-bold text-success mb-2">Forgot your password?</h2>
    <p class="text-muted mb-4">
        No worries! Enter your email and we’ll send you a secure reset link.
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3 text-success" :status="session('status')" />

    @if (session('status'))
        @php
            $submittedEmail = old('email') ?? session('email_for_reset') ?? '';
        @endphp

        <div class="eco-success mb-4"
            role="button"
            onclick="openEmailProvider(this)"
            title="Open your email inbox"
            data-email="{{ $submittedEmail }}">
            <i class="fas fa-paper-plane"></i>
            <div class="text-start">
                <strong>Reset link sent!</strong><br>
                <span class="small">Click here to open your email inbox</span>
            </div>
        </div>
    @endif

    <!-- Password Reset Form -->
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div class="form-floating mb-3 text-start">
            <input
                type="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                placeholder="name@example.com"
                value="{{ old('email') }}"
                autofocus
            >
            <label for="email">Email Address</label>

            @error('email')
                <div class="text-danger mt-1" style="font-size: 0.875rem;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-eco text-white w-100 mb-3">
            Send Password Reset Link
        </button>
    </form>

    <!-- Hint -->
    <div class="eco-hint mb-4">
        <i class="fas fa-envelope-open-text"></i>
        <span>
            Didn’t receive the email? Please check your <strong>spam</strong> or
            <strong>promotions</strong> folder.
        </span>
    </div>

    <a href="{{ route('login') }}" class="back-link">
        <i class="fas fa-arrow-left me-1"></i> Back to Login
    </a>

</div>

<script>
    function openEmailProvider(el) {
        const email = el.dataset.email;
        if (!email) {
            window.open('https://mail.google.com', '_blank');
            return;
        }

        const domain = email.split('@')[1].toLowerCase();
        let url = '';

        switch (domain) {
            case 'gmail.com':
                url = `https://mail.google.com/mail/u/?authuser=${encodeURIComponent(email)}`;
                break;

            case 'outlook.com':
            case 'hotmail.com':
            case 'live.com':
                url = 'https://outlook.live.com/mail/';
                break;

            case 'yahoo.com':
                url = 'https://mail.yahoo.com/';
                break;

            case 'icloud.com':
            case 'me.com':
                url = 'https://www.icloud.com/mail';
                break;

            default:
                url = 'https://mail.google.com';
        }

        window.open(url, '_blank');
    }
</script>

</body>
</html>
