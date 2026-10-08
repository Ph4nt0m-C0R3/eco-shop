@extends('admin.layouts.master')

@section('main_content')

<style>
    /* ===== PAGE WRAPPER ===== */
    .admin-create-page {
        min-height: calc(100vh - 80px);
        background: linear-gradient(180deg, #f6faf7, #eef6f0);
        padding: 2.5rem;
    }

    /* ===== CONTENT GRID ===== */
    .admin-create-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 2.5rem;
    }

    @media (max-width: 992px) {
        .admin-create-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ===== INFO PANEL ===== */
    .admin-info-panel {
        background: #ffffff;
        border-radius: 1.25rem;
        padding: 2rem;
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    }

    .admin-info-panel h5 {
        font-weight: 700;
        color: #2e7d32;
        margin-bottom: 1rem;
    }

    .admin-info-panel ul {
        padding-left: 0;
        list-style: none;
    }

    .admin-info-panel li {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 0.9rem;
        font-weight: 500;
    }

    .admin-info-panel i {
        color: #2e7d32;
        font-size: 1.1rem;
    }

    /* ===== FORM PANEL ===== */
    .admin-form-panel {
        background: #ffffff;
        border-radius: 1.25rem;
        padding: 2.5rem;
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    }

    .admin-form-panel h4 {
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1b5e20;
    }

    /* ===== Back Block Button (Whole Div Clickable) ===== */
    .eco-back-block {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        padding: 10px 18px;
        border-radius: 12px;

        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        font-weight: 600;

        text-decoration: none !important; /* important */
        box-shadow: 0 6px 18px rgba(34,197,94,0.25);
        transition: all 0.2s ease;
    }

    /* remove underline in ALL states */
    .eco-back-block:hover,
    .eco-back-block:focus,
    .eco-back-block:active,
    .eco-back-block:visited {
        text-decoration: none !important;
        color: white;
    }

    .eco-back-block:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(34,197,94,0.35);
    }

    .eco-back-block i {
        font-size: 14px;
    }
</style>

<div class="admin-create-page">

    {{-- PAGE HEADER --}}
    <div class="eco-header-common eco-header-sm mb-4">

        <div class="eco-header-left">
            <i class="fas fa-user-shield eco-header-icon"></i>

            <div>
                <h4 class="eco-header-title">Create Admin Account</h4>
                <p class="eco-header-subtitle">
                    Add a new administrator to manage EcoShop
                </p>
            </div>
        </div>

        <a href="{{ route('admin.settings') }}"
        class="eco-header-right eco-back-block">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Settings</span>
        </a>

    </div>

    <!-- PAGE CONTENT -->
    <div class="admin-create-grid">

        <!-- LEFT FORM PANEL -->
        <div class="admin-form-panel">
            <h4>Admin Details</h4>

            <form method="POST" action="{{ route('admin.settings.admins.create') }}">
                @csrf

                <div class="form-floating mb-3 text-start">
                    <input type="text"
                        name="name"
                        class="form-control @error('name', 'createAdmin') is-invalid @enderror"
                        placeholder="Full Name"
                        value="{{ old('name') }}">
                    <label for="name">Full Name</label>

                    @error('name', 'createAdmin')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-floating mb-3 text-start">
                    <input type="email"
                        name="email"
                        id="email"
                        class="form-control @error('email', 'createAdmin') is-invalid @enderror"
                        placeholder="Email Address"
                        value="{{ old('email') }}">
                    <label for="email">Email Address</label>

                    @error('email', 'createAdmin')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-floating password-wrapper text-start">
                    <input type="password"
                        name="password"
                        id="admin_password"
                        class="form-control @error('password', 'createAdmin') is-invalid @enderror"
                        placeholder="Password">
                    <label for="admin_password">Password</label>

                    <i class="fa-solid fa-eye-slash password-toggle"
                    onclick="togglePassword('admin_password', this)"></i>
                </div>
                @error('password', 'createAdmin')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <div class="form-floating password-wrapper mt-3 text-start">
                    <input type="password"
                        name="password_confirmation"
                        id="admin_password_confirmation"
                        class="form-control @error('password_confirmation', 'createAdmin') is-invalid @enderror"
                        placeholder="Confirm Password">
                    <label for="admin_password_confirmation">Confirm Password</label>

                    <i class="fa-solid fa-eye-slash password-toggle"
                    onclick="togglePassword('admin_password_confirmation', this)"></i>
                </div>
                @error('password_confirmation', 'createAdmin')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <div class="d-flex justify-content-end mt-4 gap-3">
                    <button type="submit" class="btn btn-success btn-eco">
                        <i class="fas fa-user-plus"></i> Create Admin
                    </button>
                </div>

            </form>
        </div>

        <!-- RIGHT INFO PANEL -->
        <div class="admin-info-panel">
            <h5>Admin Permissions</h5>

            <ul>
                <li><i class="fas fa-check-circle"></i> Manage products & categories</li>
                <li><i class="fas fa-check-circle"></i> View & process orders</li>
                <li><i class="fas fa-check-circle"></i> Manage users (except superadmin)</li>
                <li><i class="fas fa-check-circle"></i> Access analytics & reports</li>
            </ul>

            <hr>

            <p class="text-muted mb-0">
                <strong>Note:</strong> Admins do not have access to system settings or superadmin features.
            </p>
        </div>

    </div>
</div>

@endsection
