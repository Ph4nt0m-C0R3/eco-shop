@extends('admin.layouts.master')

@section('main_content')

<style>
    /* ===== Admin Management Section ===== */
    .admin-card {
        border-radius: 1rem;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .admin-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(46,125,50,0.08), rgba(27,94,32,0.08));
        opacity: 0;
        transition: 0.3s;
    }

    .admin-card:hover::before {
        opacity: 1;
    }

    .admin-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }

    .admin-icon-box {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: #fff;
    }

    .icon-green { background: #2e7d32; }
    .icon-blue { background: #1976d2; }
    .icon-orange { background: #ef6c00; }
</style>

<div class="container-fluid my-4">

    <!-- Header -->
    <div class="eco-header-common eco-header-sm mb-4">
        <div class="eco-header-left">
            <i class="fas fa-cogs eco-header-icon"></i>
            <div>
                <h4 class="eco-header-title">System Settings</h4>
                <p class="eco-header-subtitle">
                    Manage admins, system configuration and platform controls
                </p>
            </div>
        </div>

        <span class="eco-header-right badge badge-light px-3 py-2">
            <i class="fas fa-shield-alt"></i> SuperAdmin Panel
        </span>
    </div>

    <div class="row">

        <!-- Create Admin -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.settings.admins.create.page') }}" class="text-decoration-none text-dark">
                <div class="card admin-card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="font-weight-bold text-success mb-1">Create Admin</h5>
                            <small class="text-muted">Add new admin account</small>
                        </div>
                        <div class="admin-icon-box icon-green">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- View Admins -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.settings.admins') }}" class="text-decoration-none text-dark">
                <div class="card admin-card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="font-weight-bold text-primary mb-1">Manage Admins</h5>
                            <small class="text-muted">Manage, edit & remove admins</small>
                        </div>
                        <div class="admin-icon-box icon-blue">
                            <i class="fas fa-users-cog"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- View Users -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.settings.users') }}" class="text-decoration-none text-dark">
                <div class="card admin-card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="font-weight-bold text-warning mb-1">Manage Users</h5>
                            <small class="text-muted">Manage & remove users</small>
                        </div>
                        <div class="admin-icon-box icon-orange">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- System Settings -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.settings.system.page') }}" class="text-decoration-none text-dark">
                <div class="card admin-card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="font-weight-bold text-success mb-1">System Settings</h5>
                            <small class="text-muted">Application configuration</small>
                        </div>
                        <div class="admin-icon-box icon-green">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Currency Rates -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.settings.currencies') }}" class="text-decoration-none text-dark">
                <div class="card admin-card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="font-weight-bold text-primary mb-1">Currency Rates</h5>
                            <small class="text-muted">Update MMK exchange rates</small>
                        </div>
                        <div class="admin-icon-box icon-blue">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Payment Methods -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.settings.payment_methods') }}" class="text-decoration-none text-dark">
                <div class="card admin-card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="font-weight-bold text-warning mb-1">Payment Methods</h5>
                            <small class="text-muted">Manage payment accounts</small>
                        </div>
                        <div class="admin-icon-box icon-orange">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Shipping Zones -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.settings.shipping') }}" class="text-decoration-none text-dark">
                <div class="card admin-card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="font-weight-bold text-success mb-1">Shipping Zones</h5>
                            <small class="text-muted">Myanmar delivery fees</small>
                        </div>
                        <div class="admin-icon-box icon-green">
                            <i class="fas fa-truck"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tax Settings -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.settings.tax') }}" class="text-decoration-none text-dark">
                <div class="card admin-card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="font-weight-bold text-primary mb-1">Tax Settings</h5>
                            <small class="text-muted">Order tax percentage</small>
                        </div>
                        <div class="admin-icon-box icon-blue">
                            <i class="fas fa-percent"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>
@endsection
