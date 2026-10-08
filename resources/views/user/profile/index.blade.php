@extends('user.layouts.master')

@section('content')

@php
    $editMode = request()->has('edit');
@endphp

<style>
    :root {
        --eco-green: #81c408;
        --eco-dark: #2d3436;
    }

    body {
        background: #f8f9fa;
        min-height: 100vh;
    }

    .profile-container {
        margin-top: 50px;
        padding-bottom: 50px;
    }

    .eco-card {
        background: #ffffff;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .profile-sidebar {
        padding: 2.5rem 1.5rem;
        position: sticky;
        top: 130px;
    }

    .avatar-wrapper {
        position: relative;
        width: 140px;
        margin: 0 auto 1.5rem;
    }

    .avatar-wrapper img {
        width: 140px;
        height: 140px;
        border-radius: 20px;
        object-fit: cover;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        border: 4px solid #fff;
    }

    .avatar-upload {
        position: absolute;
        bottom: -5px;
        right: -5px;
        background: var(--eco-green);
        color: white;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(129, 196, 8, 0.3);
        transition: 0.3s;
    }

    .nav-pills .nav-link {
        color: #6c757d;
        padding: 12px 20px;
        margin-bottom: 8px;
        border-radius: 12px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        background: #fcfcfc;
        border: 1px solid #f0f0f0;
    }

    .nav-pills .nav-link.active {
        background: var(--eco-green) !important;
        color: white !important;
        border-color: var(--eco-green);
        font-weight: 600;
    }

    .form-control {
        border-radius: 12px;
        padding: 12px 15px;
        background: #f8f9fa;
        transition: 0.3s;
    }

    .form-control:focus {
        background: #fff;
        box-shadow: 0 0 0 3px rgba(129, 196, 8, 0.15);
    }

    .password-wrapper {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #adb5bd;
        transition: 0.2s;
        z-index: 10;
    }

    .toggle-password:hover {
        color: var(--eco-green);
    }

    .section-title {
        font-weight: 700;
        color: var(--eco-dark);
        margin-bottom: 1.5rem;
    }

    .btn-eco {
        background: var(--eco-green);
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 600;
        transition: 0.3s;
        border: none;
    }

    .btn-eco.cancel {
        background: #f8f9fa;
        color: var(--eco-dark);
        border: 1px solid #dee2e6;
    }

    /* Stop layout shift (body + navbar) */
    body.modal-open {
        padding-right: 0 !important;
    }

    /* Force true center */
    #avatarModal .modal-dialog {
        margin-left: auto !important;
        margin-right: auto !important;
        max-width: 500px;
        width: 95%;
    }

    /* Kill Bootstrap slide transform */
    .modal.fade .modal-dialog {
        transform: none !important;
    }
</style>

@include('user.pages.partials.page-header', [
    'title' => __('profile.title'),
    'breadcrumbs' => [
        [
            'label' => __('profile.title'),
            'icon'  => 'fas fa-id-card'
        ]
    ]
])


<div class="container profile-container">
    <div class="row g-4 g-lg-5">
        <div class="col-lg-4">
            <div class="eco-card profile-sidebar text-center">
                <div class="avatar-wrapper">
                    <img id="avatarPreview" src="{{ auth()->user()->avatar_url }}" alt="Profile" referrerpolicy="no-referrer">
                    <span class="avatar-upload" data-bs-toggle="modal" data-bs-target="#avatarModal">
                        <i class="fas fa-camera"></i>
                    </span>
                </div>
                <h4 class="mb-1 fw-bold text-dark">{{ auth()->user()->name }}</h4>
                @if(auth()->user()->nickname)
                    <h6 class="mb-3 text-primary fw-bold">({{ auth()->user()->nickname }})</h6>
                @endif
                <p class="text-muted small mb-4">{{ auth()->user()->email }}</p>
                <hr class="my-4 opacity-25">
                <ul class="nav nav-pills flex-column text-start" id="profileTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active w-100" id="tab-profile" data-bs-toggle="pill" data-bs-target="#profileTab">
                            <i class="fas fa-user-circle me-2"></i>{{ __('profile.profile_overview') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link w-100" id="tab-security" data-bs-toggle="pill" data-bs-target="#securityTab">
                            <i class="fas fa-shield-alt me-2"></i>{{ __('profile.security_privacy') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link w-100" id="tab-password" data-bs-toggle="pill" data-bs-target="#passwordTab">
                            <i class="fas fa-lock me-2"></i>{{ __('profile.authentication') }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="eco-card p-4 p-md-5">
                @include('components.partials.success-alert')
                @include('components.partials.error-alert')

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="profileTab">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="section-title mb-0">{{ __('profile.personal_details') }}</h3>

                            @if(!$editMode)
                                <a href="{{ route('user.profile', ['edit' => 1]) }}"
                                class="btn btn-outline-success rounded-pill">
                                    <i class="fas fa-edit me-1"></i>{{ __('profile.edit_profile') }}
                                </a>
                            @endif
                        </div>

                        {{-- ================= SHOW MODE ================= --}}
                        @if(!$editMode)
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light">
                                        <small class="text-muted fw-bold">{{ __('profile.full_name') }}</small>
                                        <div class="fw-semibold text-dark">{{ auth()->user()->name }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light">
                                        <small class="text-muted fw-bold">{{ __('profile.nickname') }}</small>
                                        <div class="fw-semibold text-dark">
                                            {{ auth()->user()->nickname ?? '—' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light">
                                        <small class="text-muted fw-bold">{{ __('profile.phone') }}</small>
                                        <div class="fw-semibold text-dark">
                                            {{ auth()->user()->phone ?? '—' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light">
                                        <small class="text-muted fw-bold">{{ __('profile.address') }}</small>
                                        <div class="fw-semibold text-dark">
                                            {{ auth()->user()->address ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        {{-- ================= EDIT MODE ================= --}}
                        @else
                            <form method="POST" action="{{ route('user.profile.update') }}">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">{{ __('profile.full_name') }}</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', auth()->user()->name) }}">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">{{ __('profile.nickname') }}</label>
                                        <input type="text" name="nickname"
                                            class="form-control @error('nickname') is-invalid @enderror"
                                            value="{{ old('nickname', auth()->user()->nickname) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">{{ __('profile.phone_number') }}</label>
                                        <input type="text" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', auth()->user()->phone) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">{{ __('profile.address') }}</label>
                                        <input type="text" name="address"
                                            class="form-control @error('address') is-invalid @enderror"
                                            value="{{ old('address', auth()->user()->address) }}">
                                    </div>
                                </div>

                                <div class="mt-4 d-flex gap-3">
                                    <button class="btn btn-eco text-white">
                                        {{ __('profile.save_changes') }} <i class="fas fa-check-circle ms-2"></i>
                                    </button>

                                    <a href="{{ route('user.profile') }}"
                                    class="btn btn-eco cancel rounded-pill px-4">
                                        {{ __('profile.cancel') }}
                                    </a>
                                </div>
                            </form>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="securityTab">
                        <h3 class="section-title">{{ __('profile.security_settings') }}</h3>
                        <div class="p-4 rounded-4" style="background: #f0f7ff; border: 1px dashed #009efd;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle text-primary fs-3 me-3"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ __('profile.enhanced_security') }}</h6>
                                    <p class="mb-0 small text-muted">{{ __('profile.security_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="passwordTab">
                        <h3 class="section-title">{{ __('profile.change_password') }}</h3>
                        <form method="POST" action="{{ route('user.password.change') }}">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">{{ __('profile.current_password') }}</label>
                                <div class="password-wrapper">
                                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror shadow-none pe-5" placeholder="{{ __('profile.enter_current_password') }}">
                                    <i class="fas fa-eye-slash toggle-password"></i>
                                </div>
                                @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">{{ __('profile.new_password') }}</label>
                                    <div class="password-wrapper">
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror shadow-none pe-5" placeholder="{{ __('profile.min_password') }}">
                                        <i class="fas fa-eye-slash toggle-password"></i>
                                    </div>
                                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">{{ __('profile.confirm_password') }}</label>
                                    <div class="password-wrapper">
                                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror shadow-none pe-5" placeholder="{{ __('profile.repeat_password') }}">
                                        <i class="fas fa-eye-slash toggle-password"></i>
                                    </div>
                                    @error('password_confirmation') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button class="btn btn-eco text-white px-4">{{ __('profile.update_credentials') }}</button>
                                <a href="{{ route('user.password.otp.request') }}" class="small text-primary fw-bold text-decoration-none">{{ __('profile.forgot_old_password') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg position-relative">

            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="fas fa-camera text-success me-2"></i>
                    {{ __('profile.update_photo') }}
                </h5>
                <button type="button"
                        class="eco-modal-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('user.profile.avatar') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="avatarForm">
                @csrf

                <div class="modal-body text-center">

                    <img id="modalAvatarPreview"
                         src="{{ auth()->user()->avatar_url }}"
                         class="rounded-circle mb-3"
                         style="width:140px;height:140px;object-fit:cover;">

                    <input type="file"
                           name="profile"
                           id="profileUploadModal"
                           class="d-none"
                           accept="image/*"
                           onchange="previewAvatar(event)">

                    <div class="mt-4 d-flex justify-content-center gap-3">

                        <button type="button"
                                class="btn btn-outline-success rounded-pill px-4"
                                onclick="document.getElementById('profileUploadModal').click()">
                            {{ __('profile.change') }}
                        </button>

                        @if(auth()->user()->profile)
                        <button type="button"
                                class="btn btn-outline-danger rounded-pill px-4"
                                onclick="showDeleteConfirm('{{ route('user.profile.avatar.delete') }}')">
                            {{ __('profile.delete') }}
                        </button>
                        @endif
                    </div>
                </div>

                <div class="modal-footer border-0 justify-content-center">
                    <button type="button"
                            class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">
                        {{ __('profile.cancel') }}
                    </button>

                    <button type="submit"
                            id="avatarUpdateBtn"
                            class="btn btn-success rounded-pill px-4"
                            disabled>
                        {{ __('profile.save_changes') }}
                    </button>
                </div>
            </form>

            {{-- Delete confirmation --}}
            @include('components.confirm-modal')

        </div>
    </div>
</div>

<script>
    // Logic to toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                input.type = 'password';
                this.classList.replace('fa-eye', 'fa-eye-slash');
            }
        });
    });

    // Logic to switch to Password Tab if validation errors occur
    document.addEventListener("DOMContentLoaded", function() {
        @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation') || session('error'))
            var passwordTab = new bootstrap.Tab(document.querySelector('#tab-password'));
            passwordTab.show();
        @endif
    });
</script>
@endsection
