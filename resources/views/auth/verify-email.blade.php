<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EcoShop | Verify Email</title>

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

    <link href="{{ asset('global/css/eco-common.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f6faf7;
            margin: 0;
            overflow: hidden;
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
            animation: slideInLeft 1.2s ease forwards;
        }

        /* Floating blobs */
        .eco-side::before,
        .eco-side::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            animation: floatBlob 12s ease-in-out infinite alternate;
        }

        .eco-side::before {
            top: -140px;
            right: -140px;
            width: 320px;
            height: 320px;
            background: rgba(46, 204, 113, 0.25);
        }

        .eco-side::after {
            bottom: -160px;
            left: -160px;
            width: 360px;
            height: 360px;
            background: rgba(39, 174, 96, 0.25);
            animation-delay: 3s;
        }

        .eco-logo {
            width: 100%;
            max-width: 150px;
            margin-bottom: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: logoFloat 3s ease-in-out infinite, fadeUp 1s ease forwards;
        }

        .eco-logo img {
            width: 100%;
            height: auto;
            max-width: 150px;
            object-fit: contain;
        }

        .eco-side h1 {
            opacity: 0;
            animation: fadeUp 0.9s ease forwards;
            animation-delay: 0.4s;
        }

        .eco-side p,
        .eco-side ul li {
            opacity: 0;
            animation: fadeUp 0.9s ease forwards;
        }

        .eco-side p { animation-delay: 0.6s; }
        .eco-side ul li:nth-child(1) { animation-delay: 0.8s; }
        .eco-side ul li:nth-child(2) { animation-delay: 1.0s; }
        .eco-side ul li:nth-child(3) { animation-delay: 1.2s; }

        /* ================= RIGHT PANEL ================= */
        .form-side {
            padding: 40px 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: slideInRight 1.1s ease forwards;
        }

        .form-wrapper {
            max-width: 480px;
            width: 100%;
            text-align: center;
            opacity: 0;
            animation: fadeUp 1s ease forwards;
            animation-delay: 0.5s;
        }

        .alert {
            animation: popIn 0.6s ease forwards;
        }

        /* ================= BUTTON ================= */
        .btn-eco {
            background-color: #27ae60;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-eco:hover {
            background-color: #1e8449;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        }

        /* ================= ANIMATIONS ================= */
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-90px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(90px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes floatBlob {
            from { transform: translate(0,0); }
            to { transform: translate(30px,-40px); }
        }

        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* ===== FIX ECO MODAL SIZE FOR AUTH PAGE ===== */

        #ecoCancelModal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        #ecoCancelModal.show {
            display: flex;
        }

        #ecoCancelModal .eco-modal {
            width: 100%;
            max-width: 560px;
            border-radius: 20px;
            animation: popIn 0.3s ease forwards;
        }

        /* Better spacing */
        #ecoCancelModal .eco-modal-body {
            padding: 30px 35px;
            font-size: 15px;
        }

        #ecoCancelModal .eco-modal-footer {
            padding: 20px 30px 30px;
        }

        @media (max-width: 576px) {
            #ecoCancelModal .eco-modal {
                max-width: 92%;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid auth-container">
    <div class="row h-100">

        <!-- LEFT PANEL -->
        <div class="col-lg-6 d-none d-lg-flex eco-side flex-column justify-content-center">
            <div class="eco-logo mb-4">
                <img
                    src="{{ setting('logo')
                        ? asset('storage/' . setting('logo'))
                        : asset('default/logo.png') }}"
                    alt="{{ setting('app_name', 'EcoShop') }}"
                >
            </div>

            <h1 class="fw-bold mb-3">EcoShop</h1>

            <p class="fs-4 mb-4">
                One more step towards a greener future
            </p>

            <ul class="list-unstyled fs-5">
                <li class="mb-3"><i class="fas fa-envelope-open-text me-3"></i> Verify your email</li>
                <li class="mb-3"><i class="fas fa-shield-alt me-3"></i> Secure your account</li>
                <li><i class="fas fa-leaf me-3"></i> Start shopping sustainably</li>
            </ul>
        </div>

        <!-- RIGHT PANEL -->
        <div class="col-lg-6 col-12 form-side">
            <div class="form-wrapper">

                <h2 class="fw-bold text-success mb-3">Verify Your Email</h2>

                <p class="text-muted mb-4">
                    We’ve sent a verification link to your email address.
                    Please click the link to activate your account.
                </p>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success small">
                        A new verification link has been sent to your email.
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-eco text-white w-100 mb-3">
                        <i class="fas fa-paper-plane me-2"></i>
                        Resend Verification Email
                    </button>
                </form>

                <p class="small text-muted">
                    Click below to open Gmail with your registered email.
                </p>

                @php
                    $userEmail = auth()->user()->email ?? '';
                @endphp

                <a href="https://mail.google.com/mail/?authuser={{ urlencode($userEmail) }}"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn-outline-success w-100 mb-2">
                    <i class="fas fa-envelope-open-text me-2"></i>
                    Open Gmail ({{ $userEmail }})
                </a>

                <!-- Cancel Registration Button -->
                <button type="button"
                        class="btn btn-link text-danger w-100 mt-3"
                        onclick="openEcoModal()">
                    <i class="fas fa-times-circle me-1"></i>
                    I don’t have access to this email address
                </button>

            </div>
        </div>

    </div>
</div>

<!-- ECO Cancel Registration Modal -->
<div class="eco-modal-overlay" id="ecoCancelModal">
    <div class="eco-modal">

        <!-- Header -->
        <div class="eco-modal-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-exclamation-triangle text-danger"></i>
                <strong class="eco-section-title">Cancel Registration</strong>
            </div>

            <button type="button"
                    class="eco-modal-close"
                    onclick="closeEcoModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="eco-modal-body text-center">
            <p class="mb-2">
                Your account has not been verified yet.
            </p>

            <p class="text-muted small mb-0">
                If you continue, your account will be permanently deleted.
            </p>
        </div>

        <!-- Footer -->
        <div class="eco-modal-footer eco-btn-group-sm">

            <button type="button"
                    class="btn btn-outline-secondary rounded-pill px-4"
                    onclick="closeEcoModal()">
                Keep My Account
            </button>

            <form method="POST" action="{{ route('registration.cancel') }}">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="btn eco-save-btn text-white rounded-pill px-4"
                        style="background: linear-gradient(135deg, #c62828, #8e0000);">
                    Yes, Delete Account
                </button>
            </form>

        </div>

    </div>
</div>

<script>
    function openEcoModal() {
        document.getElementById('ecoCancelModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeEcoModal() {
        document.getElementById('ecoCancelModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    // Close when clicking outside
    document.getElementById('ecoCancelModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEcoModal();
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
