@extends('user.layouts.master')

@section('content')

@include('user.pages.partials.page-header', [
    'title' => __('security.verify_otp'),
    'breadcrumbs' => [
        [
            'label' => __('profile.title'),
            'url'   => route('user.profile'),
            'icon'  => 'fas fa-id-card'
        ],
        [
            'label' => __('security.verify_otp'),
            'icon'  => 'fas fa-unlock-alt'
        ]
    ]
])

<div class="container-fluid my-5">
    <div class="container d-flex justify-content-center">
        <div class="eco-card border-0 shadow-lg p-4 p-md-5" style="max-width: 500px; width: 100%; border-radius: 20px; background: #fff;">

            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-unlock-alt text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h3 class="fw-bold text-dark">{{ __('security.enter_otp') }}</h3>
                <p class="text-muted small">
                    {{ __('security.otp_sent_desc') }}
                </p>
            </div>

            @include('components.partials.success-alert')
            @include('components.partials.error-alert')

            <form method="POST" action="{{ route('user.password.otp.verify') }}" onsubmit="combineOtp()">
                @csrf

                <div class="d-flex justify-content-between mb-4 mt-2" id="otp-container">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text"
                               maxlength="1"
                               inputmode="numeric"
                               autocomplete="one-time-code"
                               class="otp-box text-center fw-bold text-primary"
                               data-index="{{ $i }}"
                               style="width: 15%; height: 65px; font-size: 1.5rem; border: 2px solid #eee; border-radius: 12px; background: #f9f9f9; outline: none; transition: all 0.3s;">
                    @endfor
                </div>

                <input type="hidden" name="otp" id="otp">

                <div class="text-center mt-3">
                    <p class="small text-muted">
                        {{ __('security.otp_expires_in') }}
                        <span id="otp-timer" class="fw-bold text-danger"></span>
                    </p>
                </div>

                @error('otp')
                    <div class="text-danger text-center small mb-3">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                    </div>
                @enderror

                <button type="submit" class="btn btn-primary border-0 w-100 py-3 rounded-pill text-white fw-bold shadow-sm hover-lift">
                    <i class="fas fa-leaf me-2"></i> {{ __('security.verify_continue') }}
                </button>
            </form>

            <div class="text-center mt-4 pt-3 border-top">
                <p class="small text-muted mb-1">{{ __('security.no_code') }}</p>
                <a id="resend-otp-btn"
                href="#"
                class="text-primary fw-bold text-decoration-none hover-underline disabled"
                style="pointer-events:none; opacity:0.5;">
                    <i class="fas fa-redo-alt me-1" style="font-size: 0.8rem;"></i> {{ __('security.resend_otp') }}
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .page-header { margin-top: 90px; }

    .otp-box:focus {
        border-color: #81c408 !important; /* Eco Green */
        background: #fff !important;
        box-shadow: 0 5px 15px rgba(129, 196, 8, 0.15);
        transform: translateY(-3px);
    }

    /* Visual hint when a box is filled */
    .otp-box.filled {
        border-color: #e8f5e9;
        background: #f1f8e9 !important;
    }

    .hover-lift { transition: 0.3s; }
    .hover-lift:hover { transform: translateY(-2px); filter: brightness(1.05); }

    .hover-underline:hover { text-decoration: underline !important; }

    /* Responsive adjustment for small screens */
    @media (max-width: 450px) {
        .otp-box { height: 55px !important; border-radius: 8px; }
    }

    #otp-timer {
        display: inline-block;
    }

    .pulse {
        display: inline-block;
        animation: pulse-red 1s infinite;
    }

    @keyframes pulse-red {
        0%   { color: #dc3545; transform: scale(1); }
        50%  { color: #ff6b6b; transform: scale(1.1); }
        100% { color: #dc3545; transform: scale(1); }
    }
</style>

<script>
    const otpInputs = document.querySelectorAll('.otp-box');

    otpInputs.forEach((input, index) => {
        // Handle typing and auto-focus next
        input.addEventListener('input', (e) => {
            input.value = input.value.replace(/[^0-9]/g, '');

            if (input.value) {
                input.classList.add('filled');
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            } else {
                input.classList.remove('filled');
            }
        });

        // Handle Backspace
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });

        // Handle Paste
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const data = e.clipboardData.getData('text').slice(0, 6);
            if (/^\d+$/.test(data)) {
                data.split('').forEach((char, i) => {
                    if (otpInputs[index + i]) {
                        otpInputs[index + i].value = char;
                        otpInputs[index + i].classList.add('filled');
                        if (otpInputs[index + i + 1]) otpInputs[index + i + 1].focus();
                    }
                });
            }
        });
    });

    function combineOtp() {
        let otp = '';
        otpInputs.forEach(i => otp += i.value);
        document.getElementById('otp').value = otp;
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        let remaining = Math.floor({{ $remainingSeconds ?? 0 }});
        let interval;

        const timerEl = document.getElementById("otp-timer");
        const resendBtn = document.getElementById("resend-otp-btn");
        const submitBtn = document.querySelector('button[type="submit"]');

        function formatTime(s) {
            return String(Math.floor(s/60)).padStart(2,'0') + ":" +
                String(s%60).padStart(2,'0');
        }

        function enableResend() {
            resendBtn.classList.remove("disabled");
            resendBtn.style.pointerEvents = "auto";
            resendBtn.style.opacity = "1";
            resendBtn.classList.add("text-danger");
        }

        function disableResend() {
            resendBtn.classList.add("disabled");
            resendBtn.style.pointerEvents = "none";
            resendBtn.style.opacity = "0.5";
            resendBtn.classList.remove("text-danger");
        }

        function expireOtp() {
            clearInterval(interval);
            timerEl.textContent = "OTP expired";
            timerEl.classList.remove("pulse");
            submitBtn.disabled = true;
            enableResend();
        }

        function startTimer(seconds) {
            clearInterval(interval);
            remaining = Math.floor(seconds);

            submitBtn.disabled = false;
            disableResend();

            timerEl.textContent = formatTime(remaining);
            if (remaining <= 30 && remaining > 0) {
                timerEl.classList.add("pulse");
            }
            interval = setInterval(() => {

                if (remaining <= 0) {
                    expireOtp();
                    return;
                }

                timerEl.textContent = formatTime(remaining);

                if (remaining <= 30 && remaining > 0) {
                    timerEl.classList.add("pulse");
                } else {
                    timerEl.classList.remove("pulse");
                }

                remaining--;

            }, 1000);
        }

        // ===== RESEND AJAX =====
        resendBtn.addEventListener("click", function(e){
            e.preventDefault();

            if (resendBtn.classList.contains("disabled")) return;

            fetch("{{ route('user.password.otp.resend') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                startTimer(data.remainingSeconds);
                timerEl.classList.remove("pulse");
            });
        });

        startTimer(remaining);

    });
</script>

@endsection
