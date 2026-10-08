@extends('user.layouts.master')

@section('content')

@include('user.pages.partials.page-header', [
    'title' => __('security.reset_password'),
    'breadcrumbs' => [
        [
            'label' => __('profile.title'),
            'url'   => route('user.profile'),
            'icon'  => 'fas fa-id-card'
        ],
        [
            'label' => __('security.reset_password'),
            'icon'  => 'fas fa-lock'
        ]
    ]
])

<div class="container-fluid my-5">
    <div class="container d-flex justify-content-center">
        <div class="eco-card border-0 shadow-lg p-4 p-md-5" style="max-width: 500px; width: 100%; border-radius: 20px; background: #fff;">

            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-lock text-primary" style="font-size: 2.2rem;"></i>
                </div>
                <h4 class="fw-bold text-dark">{{ __('security.set_new_password') }}</h4>
                <p class="text-muted small">{{ __('security.password_desc') }}</p>
            </div>

            @include('components.partials.success-alert')
            @include('components.partials.error-alert')

            <form method="POST" action="{{ route('user.password.resetOtp') }}">
                @csrf

                <div class="mb-3 position-relative">
                    <div class="form-floating password-wrapper">
                        <input type="password"
                            name="password"
                            id="newPassword"
                            class="form-control bg-light @error('password') is-invalid @enderror"
                            placeholder="{{ __('security.new_password') }}"
                            autocomplete="new-password"
                            onkeyup="checkStrength(this.value)">

                        <label for="newPassword">{{ __('security.new_password') }}</label>

                        <i class="fas fa-eye-slash password-toggle"
                           onclick="togglePassword('newPassword', this)"></i>
                    </div>

                    <div class="progress mt-2" style="height: 6px; border-radius: 10px; background: #eee;">
                        <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%; transition: 0.4s;"></div>
                    </div>

                    @error('password')
                        <small class="text-danger mt-1 d-block small">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4 position-relative">
                    <div class="form-floating password-wrapper">
                        <input type="password"
                            name="password_confirmation"
                            id="confirmPassword"
                            class="form-control bg-light @error('password_confirmation') is-invalid @enderror"
                            placeholder="{{ __('security.confirm_password') }}"
                            autocomplete="new-password">

                        <label for="confirmPassword">{{ __('security.confirm_password') }}</label>

                        <i class="fas fa-eye-slash password-toggle"
                           onclick="togglePassword('confirmPassword', this)"></i>
                    </div>
                    @error('password_confirmation')
                        <small class="text-danger mt-1 d-block small">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary border-0 w-100 py-3 rounded-pill text-white fw-bold shadow-sm transition-all hover-lift">
                    <i class="fas fa-check-circle me-2"></i> {{ __('security.update_password') }}
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .page-header { margin-top: 90px; }

    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #adb5bd;
        z-index: 10;
        transition: color 0.3s;
        /* Force font size so it's visible */
        font-size: 1.2rem;
    }

    .password-toggle:hover {
        color: #81c408;
    }

    .form-floating > .form-control:focus ~ label {
        color: #81c408;
    }

    .hover-lift:hover { transform: translateY(-3px); filter: brightness(1.05); }
</style>

<script>
    function togglePassword(inputId, iconElement) {
        const input = document.getElementById(inputId);

        if (input.type === "password") {
            input.type = "text";
            iconElement.classList.remove('fa-eye-slash');
            iconElement.classList.add('fa-eye');
        } else {
            input.type = "password";
            iconElement.classList.remove('fa-eye');
            iconElement.classList.add('fa-eye-slash');
        }
    }

    function checkStrength(password) {
        let strength = 0;
        const bar = document.getElementById('strengthBar');
        if (password.length > 7) strength += 25;
        if (password.match(/[A-Z]/)) strength += 25;
        if (password.match(/[0-9]/)) strength += 25;
        if (password.match(/[^A-Za-z0-9]/)) strength += 25;

        bar.style.width = strength + "%";
        if (strength <= 25) bar.className = "progress-bar bg-danger";
        else if (strength <= 50) bar.className = "progress-bar bg-warning";
        else if (strength <= 75) bar.className = "progress-bar bg-info";
        else bar.className = "progress-bar bg-success";
    }
</script>

@endsection
