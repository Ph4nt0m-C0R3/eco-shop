<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EcoShop | Login</title>

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

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('global/css/eco-common.css') }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f6faf7;
        }

        input[type="password"]::-ms-reveal {
            display: none;
        }

        .auth-container {
            height: 100vh;
        }

        /* LEFT PANEL */
        .form-side {
            padding: 40px 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: slideInLeft 1s ease forwards;
        }

        /* DIVIDER */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #999;
            font-size: 0.85rem;
            margin: 25px 0;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #ddd;
        }

        /* SOCIAL LOGIN BUTTONS */
        .social-buttons {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;

            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            color: #fff;
            text-decoration: none;

            transition: transform 0.25s ease, box-shadow 0.25s ease, opacity 0.25s ease;
        }

        .btn-social i {
            font-size: 1.2rem;
        }

        .btn-social:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.25);
            opacity: 0.95;
        }

        /* PROVIDERS */
        .btn-google {
            background: #db4437;
        }

        .btn-github {
            background: #24292e;
        }

        /* RIGHT PANEL (REDESIGNED) */
        .eco-side {
            background: linear-gradient(160deg, #e8f8f1, #b7e4c7, #74c69d);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px;
            animation: slideInRight 1s ease forwards;
        }

        .eco-card {
            background: rgba(255,255,255,0.85);
            /* For Safari */
            -webkit-backdrop-filter: blur(10px);
            /* Standard syntax */
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 50px;
            max-width: 460px;
            text-align: center;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
        }

        .eco-logo {
            width: 100%;
            margin: 0 auto 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: logoFloat 4s ease-in-out infinite;
        }

        .eco-logo img {
            width: 100%;
            max-width: 150px;
            height: auto;
            object-fit: contain;
        }

        .eco-features {
            text-align: left;
            margin-top: 30px;
        }

        .eco-features li {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
            font-size: 1.05rem;
            color: #2d6a4f;
        }

        .eco-features i {
            font-size: 1.3rem;
            color: #27ae60;
        }

        .eco-logo {
            animation: logoFloat 4s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        /* ========= ECO BACKGROUND FLOAT ========= */
        .eco-side {
            background-size: 300% 300%;
            animation: ecoGradient 12s ease infinite;
        }

        @keyframes ecoGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ========= ECO CARD BREATH ========= */
        .eco-card {
            animation: ecoBreath 6s ease-in-out infinite;
        }

        @keyframes ecoBreath {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        /* ========= FEATURE REVEAL ========= */
        .eco-features li {
            opacity: 0;
            transform: translateY(12px);
            animation: featureReveal 0.8s ease forwards;
        }

        .eco-features li:nth-child(1) { animation-delay: 0.3s; }
        .eco-features li:nth-child(2) { animation-delay: 0.5s; }
        .eco-features li:nth-child(3) { animation-delay: 0.7s; }

        @keyframes featureReveal {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ========= SOCIAL ICON MICRO-INTERACTION ========= */
        .social-icon {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .social-icon:hover {
            transform: translateY(-6px) scale(1.05);
            box-shadow: 0 12px 25px rgba(0,0,0,0.25);
        }

        /* ========= INPUT FOCUS SOFT GLOW ========= */
        .form-control {
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
        }

        /* ANIMATIONS */
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-60px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(60px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* ================= FLOATING ECO BUBBLES ================= */
        .eco-side {
            position: relative; /* make panel relative for bubbles */
            overflow: hidden;
        }

        /* bubbles container */
        .eco-bubbles {
            position: absolute;
            inset: 0; /* fill the panel */
            z-index: 0; /* behind everything */
            pointer-events: none;
        }

        /* individual bubbles */
        .eco-bubbles span {
            position: absolute;
            bottom: -150px;
            background: rgba(39, 174, 96, 0.18); /* match your theme */
            border-radius: 50%;
            filter: blur(2px);
            animation: bubbleFloat 18s linear infinite;
        }

        /* bubble sizes and positions */
        .eco-bubbles span:nth-child(1) { width: 120px; height: 120px; left: 10%; animation-duration: 22s; }
        .eco-bubbles span:nth-child(2) { width: 80px; height: 80px; left: 25%; animation-duration: 18s; animation-delay: 3s; }
        .eco-bubbles span:nth-child(3) { width: 150px; height: 150px; left: 50%; animation-duration: 26s; animation-delay: 6s; }
        .eco-bubbles span:nth-child(4) { width: 60px; height: 60px; left: 70%; animation-duration: 20s; animation-delay: 2s; }
        .eco-bubbles span:nth-child(5) { width: 100px; height: 100px; left: 85%; animation-duration: 24s; animation-delay: 4s; }

        /* bubble animation */
        @keyframes bubbleFloat {
            0% { transform: translateY(0) scale(1); opacity: 0; }
            10% { opacity: 1; }
            100% { transform: translateY(-120vh) scale(1.15); opacity: 0; }
        }

        /* make sure card is above bubbles */
        .eco-card {
            position: relative;
            z-index: 1;
        }

        /* ===== Back To Home Link ===== */
        .back-home-link {
            display: inline-flex;
            align-items: center;
            font-weight: 600;
            font-size: 0.95rem;
            color: #2d6a4f;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .back-home-link:hover {
            color: #1b5e20;
            transform: translateX(-4px);
        }
    </style>
</head>

<body>

<div class="container-fluid auth-container">
    <div class="row h-100">

        <!-- LEFT -->
        <div class="col-lg-6 col-12 form-side">
            <div class="form-wrapper">

                <h2 class="fw-bold text-success mb-2">Welcome Back</h2>
                <p class="text-muted mb-4">Login to continue your eco-friendly journey!</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-floating mb-3 text-start">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email"
                               value="{{ old('email') }}">
                        <label for="email">Email Address</label>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3 text-start">
                        <div class="form-floating password-wrapper">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password">
                            <label for="password">Password</label>

                            <i class="fa-solid fa-eye-slash fa-fw password-toggle"
                            onclick="togglePassword('password', this)"></i>
                        </div>

                        @error('password')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="remember"
                                name="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <a href="{{ route('password.request') }}" class="small text-success fw-semibold">
                            Forgot password?
                        </a>
                    </div>

                    <button type="submit" class="btn btn-eco text-white w-100 mb-3">Login</button>
                </form>

                <div class="mb-4">
                    <a href="{{ url('/') }}" class="back-home-link">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Home
                    </a>
                </div>

                <div class="divider"> OR </div>

                <div class="social-buttons mb-3">
                    <a href="{{ route('socialLogin', ['provider' => 'google']) }}"
                    class="btn-social btn-google w-100">
                        <i class="fab fa-google"></i>
                        Continue with Google
                    </a>

                    <a href="{{ route('socialLogin', ['provider' => 'github']) }}"
                    class="btn-social btn-github w-100">
                        <i class="fab fa-github"></i>
                        Continue with GitHub
                    </a>
                </div>

                <p class="small">
                    Don’t have an account?
                    <a href="{{ route('register') }}" class="text-success fw-semibold">Sign up</a>
                </p>
            </div>
        </div>

        <!-- RIGHT (REDESIGNED) -->
        <div class="col-lg-6 d-none d-lg-flex eco-side">

            <!-- Floating eco bubbles -->
            <div class="eco-bubbles">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="eco-card">
                <div class="eco-logo">
                    <img
                        src="{{ setting('logo')
                            ? asset('storage/' . setting('logo'))
                            : asset('default/logo.png') }}"
                        alt="{{ setting('app_name', 'EcoShop') }}"
                    >
                </div>

                <h2 class="fw-bold mb-3 text-success">EcoShop</h2>
                <p class="text-muted">
                    Sustainable products crafted for a cleaner, greener future.
                </p>

                <ul class="list-unstyled eco-features">
                    <li><i class="fas fa-leaf"></i> Eco-friendly materials</li>
                    <li><i class="fas fa-recycle"></i> Reduce waste & emissions</li>
                    <li><i class="fas fa-globe"></i> Support green innovation</li>
                </ul>
            </div>
        </div>

    </div>
</div>

<!-- Custom JS -->
<script src="{{ asset('global/js/eco-common.js') }}"></script>

</body>
</html>
