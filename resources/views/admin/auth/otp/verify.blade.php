<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify OTP | EcoShop</title>

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
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .otp-inputs input {
            width: 52px;
            height: 56px;
            text-align: center;
            font-size: 1.25rem;
            font-weight: 600;
            border-radius: 0.75rem;
            border: 1px solid #ced4da;
            transition: all 0.2s ease;
        }

        .otp-inputs input:focus {
            border-color: var(--eco-primary);
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.15);
            outline: none;
        }
    </style>

</head>

<body class="bg-light">

<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="eco-card p-4 p-md-5" style="max-width: 480px; width:100%;">

        <!-- Header -->
        <div class="text-center mb-4">
            <i class="fas fa-key text-success fs-2 mb-2"></i>
            <h4 class="eco-section-title mb-1">Enter OTP</h4>
            <p class="text-muted small mb-0">
                Check your email and enter the 6-digit code
            </p>
        </div>

        <!-- Alerts -->
        @include('components.partials.success-alert')
        @include('components.partials.error-alert')
        @include('components.partials.warning-alert')

        <!-- Form -->
        <form method="POST" action="{{ route('admin.password.otp.verify') }}" onsubmit="combineOtp()">
            @csrf

            <!-- OTP Inputs -->
            <div class="otp-inputs justify-content-center">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text"
                        maxlength="1"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        class="otp-box"
                        data-index="{{ $i }}">
                @endfor
            </div>

            <!-- Hidden Combined OTP -->
            <input type="hidden" name="otp" id="otp">

            <p class="small text-muted text-center mt-2">
                OTP Expires in :
                <span id="otp-timer" class="fw-bold text-danger"></span>
            </p>

            @error('otp')
                <div class="text-danger text-center small mb-3">{{ $message }}</div>
            @enderror

            <button id="verifyBtn" class="btn eco-save-btn w-100 text-white">
                <i class="fas fa-check-circle me-1"></i>
                Verify OTP
            </button>
        </form>

        <div class="text-center mt-4">
            <button id="resendBtn"
                type="button"
                class="btn btn-link small text-success fw-semibold text-decoration-none"
                disabled>
                Resend OTP
            </button>
        </div>

    </div>
</div>

<script src="{{ asset('global/js/eco-common.js') }}"></script>

<script>
    const otpInputs = document.querySelectorAll('.otp-box');

    otpInputs.forEach((input, index) => {

        input.addEventListener('input', (e) => {
            // Allow only digits
            input.value = input.value.replace(/[^0-9]/g, '');

            // Move to next input automatically
            if (input.value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            // Move back on backspace
            if (e.key === 'Backspace' && !input.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
    });

    // Combine OTP before submit
    function combineOtp() {
        let otp = '';
        otpInputs.forEach(input => otp += input.value);
        document.getElementById('otp').value = otp;
    }

    // Paste full OTP support
    otpInputs[0].addEventListener('paste', function (e) {
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        if (!/^\d{6}$/.test(pasted)) return;

        pasted.split('').forEach((digit, i) => {
            otpInputs[i].value = digit;
        });

        otpInputs[5].focus();
        e.preventDefault();
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        let remaining = Math.floor({{ $remainingSeconds ?? 0 }});
        let interval;

        const timerEl = document.getElementById("otp-timer");
        const verifyBtn = document.getElementById("verifyBtn");
        const resendBtn = document.getElementById("resendBtn");

        function formatTime(s) {
            return String(Math.floor(s/60)).padStart(2,'0') + ":" +
                String(s%60).padStart(2,'0');
        }

        function startTimer(seconds) {
            clearInterval(interval);
            remaining = Math.floor(seconds);

            updateButtons();

            interval = setInterval(() => {

                if (remaining <= 0) {
                    timerEl.textContent = "OTP expired";
                    clearInterval(interval);

                    // OTP expired → disable verify, enable resend
                    verifyBtn.disabled = true;
                    resendBtn.disabled = false;

                    return;
                }

                timerEl.textContent = formatTime(remaining);
                remaining--;

                updateButtons();

            }, 1000);
        }

        function updateButtons() {
            if (remaining > 0) {
                verifyBtn.disabled = false;
                resendBtn.disabled = true;
            }
        }

        startTimer(remaining);

        const resendUrl = "{{ route('admin.password.otp.resend') }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        // Handle resend OTP
        resendBtn.addEventListener("click", async function () {

            if (resendBtn.disabled) return;

            try {
                resendBtn.disabled = true;
                resendBtn.innerText = "Sending...";

                const response = await fetch(resendUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // restart timer from backend value
                    startTimer(data.remainingSeconds);

                    resendBtn.innerText = "Resend OTP";
                }

            } catch (error) {
                console.error(error);
                resendBtn.disabled = false;
                resendBtn.innerText = "Resend OTP";
                alert("Failed to resend OTP. Try again.");
            }
        });
    });
</script>

</body>
</html>
