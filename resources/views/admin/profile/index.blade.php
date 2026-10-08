@extends('admin.layouts.master')

@section('main_content')

@php
    $editMode = request()->has('edit');
@endphp

<style>
/* ===== ECO PROFILE ===== */
.eco-profile-header {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: #fff;
    border-radius: 1.5rem;
    padding: 2.5rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.18);
    position: relative;
    overflow: hidden;
}

.eco-profile-header::after {
    content: "";
    position: absolute;
    right: -80px;
    top: -80px;
    width: 240px;
    height: 240px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
}

.eco-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid #fff;
    box-shadow: 0 12px 25px rgba(0,0,0,0.25);
    object-fit: cover;
}

.eco-role {
    background: rgba(255,255,255,0.15);
    border-radius: 2rem;
    padding: 0.35rem 1rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.eco-info {
    background: #e8f5e9;
    border-radius: 1rem;
    padding: 1rem;
    font-size: 0.9rem;
    color: #2e7d32;
}

@media (max-width: 768px) {
    .eco-profile-header {
        text-align: center;
    }
}

/* ===== ECO MODAL FOOTER (MATCH EXAMPLE) ===== */
.eco-modal-footer {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
}

</style>

<div class="container-fluid my-4">

    <!-- PROFILE HEADER -->
    <div class="eco-profile-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">

                <div class="position-relative d-inline-block">
                    <img id="avatarPreview"
                        src="{{ auth()->user()->profile && Storage::disk('public')->exists(auth()->user()->profile)
                            ? asset('storage/' . auth()->user()->profile)
                            : asset('admin/img/undraw_profile.svg') }}"
                        class="eco-avatar mb-3"
                        alt="Admin Avatar">

                    <!-- Camera icon -->
                    <span class="position-absolute"
                        style="bottom:10px; right:10px;
                                background:#2e7d32;
                                color:white;
                                border-radius:50%;
                                width:34px;
                                height:34px;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                box-shadow:0 6px 15px rgba(0,0,0,.3);
                                cursor:pointer;"
                        data-bs-toggle="modal"
                        data-bs-target="#avatarModal">
                        <i class="fas fa-camera"></i>
                    </span>
                </div>

            </div>

            <div class="col-md-9">
                <h3 class="mb-1">{{ auth()->user()->name }}</h3>
                <p class="mb-2 opacity-75">{{ auth()->user()->email }}</p>
                <span class="eco-role">
                    <i class="fas fa-user-shield mr-1"></i> {{ auth()->user()->role }}
                </span>
            </div>
        </div>
    </div>

    @include('components.partials.success-alert')
    @include('components.partials.error-alert')
    @include('components.partials.warning-alert')

    <div class="row">

        <!-- PROFILE FORM -->
        <div class="col-lg-8 mb-4">
            <div class="card eco-card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="eco-section-title mb-0">
                            <i class="fas fa-user mr-2"></i>
                            Profile Information
                        </h5>

                        @if(!$editMode)
                            <a href="{{ route('admin#profile', ['edit' => 1]) }}"
                            class="btn btn-eco text-white rounded-pill">
                                <i class="fas fa-edit mr-1"></i> Edit Profile
                            </a>
                        @endif
                    </div>

                    {{-- ================= VIEW MODE ================= --}}
                    @if(!$editMode)

                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="eco-info d-flex align-items-center gap-3">
                                    <i class="fas fa-user text-success fa-lg"></i>
                                    <div>
                                        <small class="text-muted">Full Name</small>
                                        <div class="fw-bold">{{ auth()->user()->name }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="eco-info d-flex align-items-center gap-3">
                                    <i class="fas fa-envelope text-success fa-lg"></i>
                                    <div>
                                        <small class="text-muted">Email</small>
                                        <div class="fw-bold">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="eco-info d-flex align-items-center gap-3">
                                    <i class="fas fa-phone text-success fa-lg"></i>
                                    <div>
                                        <small class="text-muted">Phone</small>
                                        <div class="fw-bold">
                                            {{ auth()->user()->phone ?? 'Not provided' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="eco-info d-flex align-items-center gap-3">
                                    <i class="fas fa-map-marker-alt text-success fa-lg"></i>
                                    <div>
                                        <small class="text-muted">Address</small>
                                        <div class="fw-bold">
                                            {{ auth()->user()->address ?? 'Not provided' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    {{-- ================= EDIT MODE ================= --}}
                    @else

                        <form method="POST" action="{{ route('admin#profile.update') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Full Name</label>
                                    <input type="text"
                                        name="name"
                                        class="form-control eco-input rounded-pill @error('name') is-invalid @enderror"
                                        value="{{ old('name', auth()->user()->name) }}">

                                    @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="email"
                                        name="email"
                                        class="form-control eco-input rounded-pill @error('email') is-invalid @enderror"
                                        value="{{ old('email', auth()->user()->email) }}">

                                    @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Phone</label>
                                    <input type="text"
                                        name="phone"
                                        class="form-control eco-input rounded-pill @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', auth()->user()->phone) }}">

                                    @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Address</label>
                                    <input type="text"
                                        name="address"
                                        class="form-control eco-input rounded-pill @error('address') is-invalid @enderror"
                                        value="{{ old('address', auth()->user()->address) }}">

                                    @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-3">
                                <a href="{{ route('admin#profile') }}"
                                class="btn btn-eco text-dark bg-gray-300 rounded-pill px-4">
                                    Cancel
                                </a>

                                <button class="btn eco-save-btn text-white px-4">
                                    <i class="fas fa-leaf mr-1"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>

                    @endif

                </div>
            </div>
        </div>

        {{-- ADMIN: can change password --}}
        @if(in_array(auth()->user()->role, ['admin','superadmin']))
        <div class="col-lg-4">
            <div class="card eco-card mb-4">
                <div class="card-body">
                    <h6 class="eco-section-title mb-3">
                        <i class="fas fa-lock mr-2"></i>
                        Security
                    </h6>

                    <div class="eco-info mb-3">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Keep your account secure by updating your password.
                    </div>

                    {{-- Last password update --}}
                    <div class="text-muted small mb-3">
                        <i class="fas fa-clock mr-1"></i>
                        Last password update :
                        <strong>
                            {{ auth()->user()->password_updated_at
                                ? auth()->user()->password_updated_at->format('d M Y')
                                : 'Never updated' }}
                        </strong>
                    </div>

                    <a href="#"
                    class="btn btn-outline-success rounded-pill btn-block"
                    data-bs-toggle="modal"
                    data-bs-target="#changePasswordModal">
                        Change Password
                    </a>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

<!-- Avatar Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="fas fa-camera text-success mr-2"></i>
                    Update Profile Photo
                </h5>

                <button type="button"
                        class="eco-modal-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('admin#profile.avatar') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="avatarForm">
                @csrf

                <div class="modal-body text-center">

                    <!-- Preview -->
                    <img id="modalAvatarPreview"
                         src="{{ auth()->user()->profile && Storage::disk('public')->exists(auth()->user()->profile)
                                ? asset('storage/' . auth()->user()->profile)
                                : asset('admin/img/undraw_profile.svg') }}"
                         class="rounded-circle mb-3"
                         style="width:140px;height:140px;object-fit:cover;box-shadow:0 12px 25px rgba(0,0,0,.25);">

                    <input type="file"
                        name="profile"
                        id="profileUploadModal"
                        class="d-none"
                        accept="image/*"
                        onchange="previewAvatar(event)">

                    <div class="eco-btn-group mt-4">

                        <!-- Change -->
                        <button type="button"
                                class="btn btn-outline-success rounded-pill px-4"
                                onclick="document.getElementById('profileUploadModal').click()">
                            <i class="fas fa-upload mr-1"></i> Change
                        </button>

                        @if(auth()->user()->profile)
                        <!-- Delete -->
                        <button type="button"
                                class="btn btn-outline-danger rounded-pill px-4"
                                onclick="showDeleteConfirm('{{ route('admin#profile.avatar.delete') }}')">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                        @endif

                    </div>

                </div>

                <div class="modal-footer border-0">

                    <small class="text-muted d-block text-center mb-3 w-100">
                        JPG, PNG, WEBP • Max 2MB
                    </small>

                    <div class="eco-modal-footer w-100">

                        <button type="button"
                                class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit"
                                id="avatarUpdateBtn"
                                class="btn btn-success rounded-pill px-4"
                                disabled>
                            <i class="fas fa-check mr-1"></i>
                            Update
                        </button>

                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Overlay -->
@include('components.confirm-modal')

@include('admin.profile.change-password-modal')

@if ($errors->has('old_password') ||
     $errors->has('password') ||
     $errors->has('password_confirmation'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(
            document.getElementById('changePasswordModal')
        );
        modal.show();
    });
</script>
@endif

@endsection
