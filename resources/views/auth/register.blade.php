<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EcoShop | Sign Up</title>

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
            margin: 0;
            background: #f6faf7;
        }

        input[type="password"]::-ms-reveal {
            display: none;
        }

        .auth-container {
            height: 100vh;
            width: 100vw;
        }

        /* ================= LEFT PANEL ================= */
        .eco-side {
            background: linear-gradient(160deg, #e8f8f1, #b7e4c7, #74c69d);
            color: #1b4332;
            padding: 80px;
            position: relative;
            overflow: hidden;
            animation: slideInLeft 1.1s ease forwards;
        }

        /* Ambient floating blobs */
        .eco-side::before,
        .eco-side::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            animation: floatBlob 12s ease-in-out infinite alternate;
        }

        .eco-side::before {
            top: -120px;
            right: -120px;
            width: 320px;
            height: 320px;
            background: rgba(46, 204, 113, 0.25);
        }

        .eco-side::after {
            bottom: -150px;
            left: -150px;
            width: 360px;
            height: 360px;
            background: rgba(39, 174, 96, 0.25);
            animation-delay: 3s;
        }

        /* Logo */
        .eco-logo {
            width: 100%;
            max-width: 150px; /* adjust size as needed */
            margin-bottom: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: logoFloat 3s ease-in-out infinite, fadeUp 1s ease forwards;
        }

        .eco-logo img {
            width: 100%;
            height: auto;
            object-fit: contain; /* keeps proportions */
        }

        /* Text animations */
        .eco-side h1 {
            opacity: 0;
            animation: fadeUp 0.9s ease forwards;
            animation-delay: 0.4s;
        }

        .eco-subtitle {
            max-width: 420px;
            line-height: 1.5;
            opacity: 0;
            animation: fadeUp 0.9s ease forwards;
            animation-delay: 0.6s;
        }

        .eco-features li {
            display: flex;
            align-items: center;
            font-weight: 500;
            opacity: 0;
            animation: fadeUp 0.7s ease forwards;
        }

        .eco-features li:nth-child(1) { animation-delay: 0.9s; }
        .eco-features li:nth-child(2) { animation-delay: 1.1s; }
        .eco-features li:nth-child(3) { animation-delay: 1.3s; }

        .eco-features i {
            color: #2d6a4f;
            font-size: 1.2rem;
        }

        /* ================= RIGHT PANEL (UNCHANGED) ================= */
        .form-side {
            padding: 40px 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: slideInRight 1s ease forwards;
        }

        /* ================= ANIMATIONS ================= */
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-80px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(80px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes floatUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes logoFloat {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes floatBlob {
            from { transform: translateY(0) translateX(0); }
            to { transform: translateY(-40px) translateX(30px); }
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

        .password-rules {
            font-size: 0.85rem;
        }

        .rule {
            display: flex;
            align-items: center;
            margin-bottom: 4px;
            color: #999;
            transition: all 0.3s ease;
        }

        .rule i {
            margin-right: 8px;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        /* Success */
        .rule.valid {
            color: #2d6a4f;
        }

        .rule.valid i {
            color: #2d6a4f;
            transform: scale(1.2);
        }

        /* Smooth fade animation */
        .rule.valid {
            animation: popIn 0.3s ease forwards;
        }

        @keyframes popIn {
            from { transform: translateX(-5px); opacity: 0.5; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Confirm Password Feedback */
        .confirm-feedback {
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #999;
            transition: all 0.3s ease;
        }

        .confirm-feedback.valid {
            color: #2d6a4f;
        }

        .confirm-feedback.invalid {
            color: #dc3545;
        }

        /* Input border animations */
        input.match-success {
            border-color: #2d6a4f !important;
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.15);
            transition: all 0.3s ease;
        }

        input.match-error {
            border-color: #dc3545 !important;
            animation: shake 0.3s ease;
        }

        /* Shake animation */
        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            50% { transform: translateX(4px); }
            75% { transform: translateX(-4px); }
            100% { transform: translateX(0); }
        }
    </style>
</head>

<body>

<div class="container-fluid auth-container">
    <div class="row h-100">

        <!-- LEFT PANEL -->
        <div class="col-lg-6 d-none d-lg-flex eco-side flex-column justify-content-center align-items-start">
            <div class="eco-logo">
                <img src="{{ setting('logo')
                    ? asset('storage/' . setting('logo'))
                    : asset('default/logo.png') }}"
                    alt="{{ setting('app_name', 'EcoShop') }}">
            </div>

            <h1 class="fw-bold mb-3">EcoShop</h1>

            <p class="fs-4 mb-4 eco-subtitle">
                Sustainable products for a cleaner, greener future.
            </p>

            <ul class="list-unstyled fs-5 eco-features">
                <li class="mb-3"><i class="fas fa-leaf me-3"></i> Eco-friendly materials</li>
                <li class="mb-3"><i class="fas fa-recycle me-3"></i> Reduce waste & emissions</li>
                <li><i class="fas fa-globe me-3"></i> Support green innovation</li>
            </ul>
        </div>

        <!-- RIGHT PANEL -->
        <div class="col-lg-6 col-12 form-side">
            <div class="form-wrapper">

                <h2 class="fw-bold text-success mb-2">Create Your Account</h2>
                <p class="text-muted mb-3">
                    Join thousands making eco-friendly choices!
                </p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-floating mb-3 text-start">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Full Name" value="{{ old('name') }}">
                        <label for="name">Full Name</label>
                        @error('name')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-floating mb-3 text-start">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email Address" value="{{ old('email') }}">
                        <label for="email">Email Address</label>
                        @error('email')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-floating mb-3 text-start">
                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" placeholder="Phone Number" value="{{ old('phone') }}">
                        <label for="phone">Phone Number</label>
                        @error('phone')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6 text-start">
                            <div class="form-floating password-wrapper">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password">
                                <label for="password">Password</label>
                                <i class="fa-solid fa-eye-slash fa-fw password-toggle" onclick="togglePassword('password', this)"></i>
                            </div>
                            @error('password')
                                <small class="text-danger d-block mt-1 server-error" id="password-error">
                                    {{ $message }}
                                </small>
                            @enderror

                            <div class="password-rules mt-2">
                                <div class="rule" id="rule-length">
                                    <i class="fa-regular fa-circle"></i> At least 8 characters
                                </div>
                                <div class="rule" id="rule-upper">
                                    <i class="fa-regular fa-circle"></i> One uppercase letter
                                </div>
                                <div class="rule" id="rule-lower">
                                    <i class="fa-regular fa-circle"></i> One lowercase letter
                                </div>
                                <div class="rule" id="rule-number">
                                    <i class="fa-regular fa-circle"></i> One number
                                </div>
                                <div class="rule" id="rule-special">
                                    <i class="fa-regular fa-circle"></i> One special character
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 text-start">
                            <div class="form-floating password-wrapper">
                                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="Repeat Password">
                                <label for="password_confirmation">Repeat Password</label>
                                <i class="fa-solid fa-eye-slash fa-fw password-toggle" onclick="togglePassword('password_confirmation', this)"></i>
                            </div>
                            @error('password_confirmation')
                                <small class="text-danger d-block mt-1 server-error" id="confirm-error">
                                    {{ $message }}
                                </small>
                            @enderror

                            <div class="confirm-feedback mt-2" id="confirm-feedback">
                                <i class="fa-regular fa-circle"></i>
                                <span>Passwords must match</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-eco text-white w-100 my-3">
                        Create Account
                    </button>
                </form>

                <p class="small">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-success fw-semibold">Login</a>
                </p>

                <div class="mb-4">
                    <a href="{{ url('/') }}" class="back-home-link">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Home
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script src="{{ asset('global/js/eco-common.js') }}"></script>

<script>
    const passwordInput = document.getElementById('password');

    const rules = {
        length: document.getElementById('rule-length'),
        upper: document.getElementById('rule-upper'),
        lower: document.getElementById('rule-lower'),
        number: document.getElementById('rule-number'),
        special: document.getElementById('rule-special')
    };

    passwordInput.addEventListener('input', function () {
        const value = this.value;

        validateRule(value.length >= 8, rules.length);
        validateRule(/[A-Z]/.test(value), rules.upper);
        validateRule(/[a-z]/.test(value), rules.lower);
        validateRule(/[0-9]/.test(value), rules.number);
        validateRule(/[^A-Za-z0-9]/.test(value), rules.special);
    });

    function validateRule(condition, element) {
        if (condition) {
            element.classList.add('valid');
            element.querySelector('i').className = 'fa-solid fa-circle-check';
        } else {
            element.classList.remove('valid');
            element.querySelector('i').className = 'fa-regular fa-circle';
        }
    }
</script>

<script>
    const confirmInput = document.getElementById('password_confirmation');
    const confirmFeedback = document.getElementById('confirm-feedback');

    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;

        if (confirm.length === 0) {
            resetConfirmState();
            return;
        }

        if (password === confirm) {
            confirmFeedback.classList.remove('invalid');
            confirmFeedback.classList.add('valid');
            confirmFeedback.querySelector('i').className = 'fa-solid fa-circle-check';
            confirmFeedback.querySelector('span').innerText = 'Passwords match';

            confirmInput.classList.remove('match-error');
            confirmInput.classList.add('match-success');

        } else {
            confirmFeedback.classList.remove('valid');
            confirmFeedback.classList.add('invalid');
            confirmFeedback.querySelector('i').className = 'fa-solid fa-circle-xmark';
            confirmFeedback.querySelector('span').innerText = 'Passwords do not match';

            confirmInput.classList.remove('match-success');
            confirmInput.classList.add('match-error');
        }
    }

    function resetConfirmState() {
        confirmFeedback.classList.remove('valid', 'invalid');
        confirmFeedback.querySelector('i').className = 'fa-regular fa-circle';
        confirmFeedback.querySelector('span').innerText = 'Passwords must match';
        confirmInput.classList.remove('match-success', 'match-error');
    }

    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmInput.addEventListener('input', checkPasswordMatch);
</script>

<script>
    function removeServerError(input, errorId) {
        input.addEventListener('input', function () {

            // Remove red invalid border
            this.classList.remove('is-invalid');

            // Remove server error message if exists
            const errorEl = document.getElementById(errorId);
            if (errorEl) {
                errorEl.remove();
            }
        });
    }

    removeServerError(passwordInput, 'password-error');
    removeServerError(confirmInput, 'confirm-error');
</script>

</body>
</html>
