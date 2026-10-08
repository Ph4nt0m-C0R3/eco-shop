@extends('admin.layouts.master')

@section('main_content')

<style>
/* =============================================
   BASE / SHARED
============================================= */
:root {
    --radius-card: 14px;
    --radius-input: 10px;
    --color-success: #16a34a;
    --color-danger: #dc2626;
    --color-info: #0891b2;
    --shadow-card: 0 2px 12px rgba(0,0,0,0.07);
    --touch-target: 44px;
}

/* =============================================
   CREATE-BAR (status + submit row)
============================================= */
.create-bar {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 16px;
}

.create-bar > div { flex: 1; }

.create-bar button { white-space: nowrap; }

/* =============================================
   ACTIVE SWITCH
============================================= */
.active-switch.form-switch { padding-left: 0; }

.active-switch {
    display: flex;
    align-items: center;
    gap: 8px;
}

.active-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
    cursor: pointer;
    margin-left: 0;
    flex-shrink: 0;
}

.active-switch .form-check-label {
    margin: 0;
    user-select: none;
    white-space: nowrap;
}

/* =============================================
   UPLOAD ZONE
============================================= */
.upload-zone {
    position: relative;
    border: 2px dashed #ced4da;
    border-radius: var(--radius-card);
    background: #f8f9fa;
    transition: border-color .2s, background .2s;
    overflow: hidden;
    cursor: pointer;
}

.upload-zone:hover,
.upload-zone:focus-within {
    border-color: var(--color-success);
    background: #f0fdf4;
}

.upload-zone input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
    z-index: 2;
}

.upload-zone-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 14px 10px;
    text-align: center;
    pointer-events: none;
}

.upload-zone-preview {
    width: 64px;
    height: 64px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #ddd;
    background: #fff;
}

.upload-zone-label {
    font-size: .78rem;
    font-weight: 600;
    color: #374151;
    line-height: 1.3;
}

.upload-zone-hint {
    font-size: .72rem;
    color: #9ca3af;
    line-height: 1.3;
}

/* =============================================
   DESKTOP TABLE
============================================= */
.desktop-table { display: block; }
.mobile-card-list { display: none; }

/* =============================================
   MOBILE PAYMENT CARD
============================================= */
.pm-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-card);
    overflow: hidden;
    margin-bottom: 14px;
}

.pm-card-head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 14px 10px;
    border-bottom: 1px solid #f3f4f6;
}

.pm-card-icon {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid #e5e7eb;
    flex-shrink: 0;
    background: #f9fafb;
}

.pm-card-icon-placeholder {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #d1d5db;
    font-size: 1.2rem;
}

.pm-card-title {
    flex: 1;
    min-width: 0;
}

.pm-card-title .name-en {
    font-weight: 700;
    font-size: .95rem;
    color: #111827;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.pm-card-title .name-mm {
    font-size: .8rem;
    color: #6b7280;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.pm-card-badges {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
    flex-shrink: 0;
}

.pm-card-body {
    padding: 12px 14px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px 16px;
}

.pm-card-field { display: flex; flex-direction: column; gap: 2px; }

.pm-card-field-label {
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #9ca3af;
}

.pm-card-field-value {
    font-size: .84rem;
    color: #1f2937;
    font-weight: 500;
    word-break: break-all;
}

.pm-card-field-value.muted { color: #9ca3af; font-weight: 400; }

.pm-card-qr-strip {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: #f9fafb;
    border-top: 1px solid #f3f4f6;
}

.pm-card-qr-strip img {
    width: 56px;
    height: 56px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.pm-card-qr-strip .qr-label {
    font-size: .78rem;
    font-weight: 600;
    color: #374151;
}

.pm-card-qr-strip .qr-sub {
    font-size: .72rem;
    color: #9ca3af;
}

.pm-card-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    border-top: 1px solid #f3f4f6;
}

.pm-card-actions .btn-action {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 11px 8px;
    font-size: .84rem;
    font-weight: 600;
    border: none;
    background: transparent;
    cursor: pointer;
    transition: background .15s;
    min-height: var(--touch-target);
}

.pm-card-actions .btn-action:first-child {
    border-right: 1px solid #f3f4f6;
    color: #2563eb;
}

.pm-card-actions .btn-action:first-child:hover,
.pm-card-actions .btn-action:first-child:active {
    background: #eff6ff;
}

.pm-card-actions .btn-action:last-child { color: #dc2626; }

.pm-card-actions .btn-action:last-child:hover,
.pm-card-actions .btn-action:last-child:active {
    background: #fef2f2;
}

.pm-index-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #374151;
    font-size: .7rem;
    font-weight: 700;
    flex-shrink: 0;
    position: absolute;
    top: 10px;
    left: 10px;
}

/* =============================================
   FORM MOBILE IMPROVEMENTS
============================================= */
@media (max-width: 767.98px) {

    /* Header */
    .eco-header-right { display: none; }

    /* Ensure nice touch targets on all form controls */
    .form-control,
    .form-select {
        min-height: var(--touch-target);
        font-size: .95rem;
        border-radius: var(--radius-input);
    }

    .form-label {
        font-size: .84rem;
        margin-bottom: 5px;
    }

    /* CREATE BAR */
    .create-bar {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
        padding: 14px !important;
    }

    .create-bar > div { width: 100%; }

    .create-bar button {
        width: 100%;
        min-height: var(--touch-target);
        font-size: 1rem;
        border-radius: var(--radius-input);
    }

    /* Table → Cards */
    .desktop-table { display: none !important; }
    .mobile-card-list { display: block; }

    /* Card inner spacing */
    .card-body { padding: 14px !important; }
    .card-header { padding: 12px 14px !important; }

    /* Row gutter tighten */
    .row.g-3 { --bs-gutter-y: .9rem; }

    /* Upload zones */
    .upload-zone-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .upload-zone-preview {
        width: 54px;
        height: 54px;
    }

    /* Container padding */
    .container-fluid { padding-left: 14px !important; padding-right: 14px !important; }
}

/* =============================================
   TABLET FIX (768px - 991px)
============================================= */
@media (min-width: 768px) and (max-width: 991.98px) {

    .create-bar {
        flex-direction: row;
        align-items: end; /* FIX: align nicely */
        gap: 12px;
        padding: 14px !important;
    }

    /* Status section */
    .create-bar > div {
        flex: 1;
        min-width: 0;
    }

    .create-bar select {
        height: 42px;
        font-size: 0.9rem;
    }

    /* Button fix */
    .create-bar button {
        flex-shrink: 0;
        height: 42px;
        padding: 0 16px;
        font-size: 0.9rem;
        border-radius: 10px;
    }

    /* Optional: shorten button text */
    .create-bar button span.d-none.d-sm-inline {
        display: none !important;
    }

    .create-bar button span.d-inline.d-sm-none {
        display: inline !important;
    }
}

/* =============================================
   DESKTOP: hide mobile cards
============================================= */
@media (min-width: 768px) {
    .mobile-card-list { display: none !important; }
    .desktop-table { display: block !important; }
    .upload-zone-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
}
</style>

<div class="container-fluid my-4">

    <!-- ============ HEADER ============ -->
    <div class="eco-header-common eco-header-sm mb-4">

        <div class="eco-header-left has-back">
            <a href="{{ route('admin.settings') }}"
               class="btn btn-light text-success rounded-circle">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div>
                <h5 class="eco-header-title mb-0">
                    <i class="fas fa-credit-card mr-1"></i>
                    Payment Methods
                </h5>
                <p class="eco-header-subtitle">
                    Create, update and manage payment options for customers
                </p>
            </div>
        </div>

        <span class="eco-header-right badge badge-light px-3 py-2">
            <i class="fas fa-shield-alt"></i> Settings Panel
        </span>

    </div>

    {{-- Alerts --}}
    @include('components.partials.success-alert')
    @include('components.partials.error-alert')
    @include('components.partials.warning-alert')

    <!-- ============ CREATE FORM ============ -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-bold">
            <i class="fas fa-plus-circle text-success"></i> Create Payment Method
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ route('admin.settings.payment_methods.create') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    {{-- Name EN + MM side by side on mobile too --}}
                    <div class="col-6 col-md-6">
                        <label class="form-label fw-bold">Name (English)</label>
                        <input type="text"
                            name="name_en"
                            value="{{ old('name_en') }}"
                            placeholder="e.g. KBZ Pay"
                            class="form-control @error('name_en') is-invalid @enderror"
                            required>
                        @error('name_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6 col-md-6">
                        <label class="form-label fw-bold">Name (Myanmar)</label>
                        <input type="text"
                            name="name_mm"
                            value="{{ old('name_mm') }}"
                            placeholder="မြန်မာ နာမည်"
                            class="form-control @error('name_mm') is-invalid @enderror">
                        @error('name_mm')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Type + Currency side by side on mobile --}}
                    <div class="col-7 col-md-6">
                        <label class="form-label fw-bold">Type</label>
                        <select name="type"
                                class="form-select @error('type') is-invalid @enderror"
                                required>
                            <option value="manual"   {{ old('type') == 'manual'   ? 'selected' : '' }}>Manual</option>
                            <option value="cod"      {{ old('type') == 'cod'      ? 'selected' : '' }}>Cash On Delivery</option>
                            <option value="bank"     {{ old('type') == 'bank'     ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="e_wallet" {{ old('type') == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="stripe"   {{ old('type') == 'stripe'   ? 'selected' : '' }}>Stripe</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-5 col-md-4">
                        <label class="form-label fw-bold">Currency</label>
                        <select name="currency_code"
                                class="form-select @error('currency_code') is-invalid @enderror"
                                required>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->code }}"
                                    {{ old('currency_code') == $currency->code ? 'selected' : '' }}>
                                    {{ $currency->code }}
                                </option>
                            @endforeach
                        </select>
                        @error('currency_code')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Account fields --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Account Name</label>
                        <input type="text"
                            name="account_name"
                            value="{{ old('account_name') }}"
                            placeholder="Account holder name"
                            class="form-control @error('account_name') is-invalid @enderror">
                        @error('account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Account Number</label>
                        <input type="text"
                            name="account_number"
                            value="{{ old('account_number') }}"
                            placeholder="Account / phone number"
                            class="form-control @error('account_number') is-invalid @enderror">
                        @error('account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Upload zones — side by side on ALL screen sizes --}}
                    <div class="col-12">
                        <div class="upload-zone-row">

                            {{-- Icon --}}
                            <div>
                                <label class="form-label fw-bold d-block mb-1">
                                    Payment Icon
                                    <span class="text-muted fw-normal">(Optional)</span>
                                </label>
                                <div class="upload-zone @error('icon') border-danger @enderror">
                                    <input type="file"
                                        name="icon"
                                        accept="image/png,image/webp,image/jpeg"
                                        onchange="previewCreateIcon(event)">
                                    <div class="upload-zone-inner">
                                        <img id="create_icon_preview"
                                            src="{{ asset('default/no-image.png') }}"
                                            class="upload-zone-preview">
                                        <span class="upload-zone-label">
                                            <i class="fas fa-image me-1 text-success"></i>Icon
                                        </span>
                                        <span class="upload-zone-hint">512×512 PNG/WebP</span>
                                    </div>
                                </div>
                                @error('icon')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- QR --}}
                            <div>
                                <label class="form-label fw-bold d-block mb-1">
                                    QR Image
                                    <span class="text-muted fw-normal">(Optional)</span>
                                </label>
                                <div class="upload-zone @error('qr_image') border-danger @enderror">
                                    <input type="file"
                                        name="qr_image"
                                        accept="image/png,image/webp,image/jpeg"
                                        onchange="previewCreateQR(event)">
                                    <div class="upload-zone-inner">
                                        <img id="create_qr_preview"
                                            src="{{ asset('default/no-image.png') }}"
                                            class="upload-zone-preview">
                                        <span class="upload-zone-label">
                                            <i class="fas fa-qrcode me-1 text-primary"></i>QR Code
                                        </span>
                                        <span class="upload-zone-hint">PNG / WebP / JPEG</span>
                                    </div>
                                </div>
                                @error('qr_image')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Status + Submit --}}
                    <div class="col-12 col-md-6">
                        <div class="create-bar mt-1 p-3 rounded"
                            style="background:#f8f9fa; border:1px solid #e5e5e5;">

                            <div>
                                <label class="form-label fw-bold">Status</label>
                                <select name="is_active"
                                        class="form-select @error('is_active') is-invalid @enderror">
                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-plus-circle"></i>
                                <span class="d-none d-sm-inline">Create Payment Method</span>
                                <span class="d-inline d-sm-none">Create</span>
                            </button>

                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- ============ PAYMENT METHODS LIST ============ -->
    <div class="card shadow-sm">
        <div class="card-header bg-light fw-bold">
            <i class="fas fa-list text-primary"></i> Payment Methods List
        </div>

        <div class="card-body">

            {{-- ====== DESKTOP TABLE ====== --}}
            <div class="table-responsive desktop-table">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:30px"  class="text-center">ID</th>
                            <th style="width:180px">Name</th>
                            <th style="width:40px"  class="text-center">Type</th>
                            <th style="width:40px"  class="text-center">Currency</th>
                            <th style="width:250px" class="text-center">Account</th>
                            <th style="width:120px" class="text-center">Icon</th>
                            <th style="width:120px" class="text-center">QR</th>
                            <th style="width:60px"  class="text-center">Status</th>
                            <th style="width:150px" class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($methods as $method)
                            <tr>
                                <td class="fw-bold text-center">{{ $loop->iteration }}</td>

                                <td>
                                    <div class="fw-bold">{{ $method->name_en }}</div>
                                    @if($method->name_mm)
                                        <div class="text-muted small">{{ $method->name_mm }}</div>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-info text-dark">
                                        {{ strtoupper($method->type) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $method->currency_code }}</span>
                                </td>

                                <td>
                                    @if($method->account_name || $method->account_number)
                                        <div><b>Name:</b> {{ $method->account_name }}</div>
                                        <div><b>No:</b> {{ $method->account_number }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($method->icon)
                                        <img src="{{ asset('storage/'.$method->icon) }}"
                                            style="width:70px;height:70px;object-fit:cover;border-radius:12px;">
                                    @else
                                        <span class="text-muted">No Icon</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($method->qr_image)
                                        <img src="{{ asset('storage/'.$method->qr_image) }}"
                                             style="width:80px;height:80px;object-fit:cover;border-radius:10px;">
                                    @else
                                        <span class="text-muted">No QR</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($method->is_active)
                                        <span class="badge bg-success">ACTIVE</span>
                                    @else
                                        <span class="badge bg-danger">INACTIVE</span>
                                    @endif
                                </td>

                                <td>
                                    <button class="btn btn-sm btn-primary w-100 mb-2"
                                            onclick="openEcoModalById('updateModal_{{ $method->id }}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    <button class="btn btn-sm btn-danger w-100"
                                            onclick="openEcoModalById('deleteModal_{{ $method->id }}')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    No payment methods found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ====== MOBILE CARD LIST ====== --}}
            <div class="mobile-card-list">
                @forelse($methods as $method)
                    <div class="pm-card">

                        {{-- Card Head: icon + name + badges --}}
                        <div class="pm-card-head">

                            {{-- Icon --}}
                            @if($method->icon)
                                <img src="{{ asset('storage/'.$method->icon) }}"
                                     class="pm-card-icon"
                                     alt="{{ $method->name_en }}">
                            @else
                                <div class="pm-card-icon-placeholder">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                            @endif

                            {{-- Name --}}
                            <div class="pm-card-title">
                                <div class="name-en">{{ $method->name_en }}</div>
                                @if($method->name_mm)
                                    <div class="name-mm">{{ $method->name_mm }}</div>
                                @endif
                                <div class="mt-1" style="font-size:.72rem;color:#9ca3af;">
                                    #{{ $loop->iteration }}
                                </div>
                            </div>

                            {{-- Badges --}}
                            <div class="pm-card-badges">
                                @if($method->is_active)
                                    <span class="badge bg-success" style="font-size:.7rem;">ACTIVE</span>
                                @else
                                    <span class="badge bg-danger" style="font-size:.7rem;">INACTIVE</span>
                                @endif
                                <span class="badge bg-info text-dark" style="font-size:.7rem;">
                                    {{ strtoupper($method->type) }}
                                </span>
                                <span class="badge bg-secondary" style="font-size:.7rem;">
                                    {{ $method->currency_code }}
                                </span>
                            </div>

                        </div>

                        {{-- Card Body: account info grid --}}
                        @if($method->account_name || $method->account_number)
                            <div class="pm-card-body">
                                @if($method->account_name)
                                    <div class="pm-card-field">
                                        <span class="pm-card-field-label">Account Name</span>
                                        <span class="pm-card-field-value">{{ $method->account_name }}</span>
                                    </div>
                                @endif
                                @if($method->account_number)
                                    <div class="pm-card-field">
                                        <span class="pm-card-field-label">Account No.</span>
                                        <span class="pm-card-field-value">{{ $method->account_number }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- QR Strip --}}
                        @if($method->qr_image)
                            <div class="pm-card-qr-strip">
                                <img src="{{ asset('storage/'.$method->qr_image) }}"
                                     alt="QR Code">
                                <div>
                                    <div class="qr-label">
                                        <i class="fas fa-qrcode me-1 text-primary"></i>QR Code
                                    </div>
                                    <div class="qr-sub">Tap to view full size</div>
                                </div>
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="pm-card-actions">
                            <button class="btn-action"
                                    onclick="openEcoModalById('updateModal_{{ $method->id }}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn-action"
                                    onclick="openEcoModalById('deleteModal_{{ $method->id }}')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>

                    </div>

                @empty
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-credit-card fa-2x mb-3 d-block" style="opacity:.3;"></i>
                        No payment methods found.
                    </div>
                @endforelse
            </div>

            @foreach($methods as $method)
                @include('admin.settings.payment_methods.update')
                @include('admin.settings.payment_methods.delete')
            @endforeach

        </div>
    </div>

</div>

<script>
    function previewCreateQR(event) {
        document.getElementById("create_qr_preview").src =
            URL.createObjectURL(event.target.files[0]);
    }

    function previewCreateIcon(event) {
        document.getElementById("create_icon_preview").src =
            URL.createObjectURL(event.target.files[0]);
    }

    function previewUpdateQR(event, id) {
        document.getElementById("qr_preview_" + id).src =
            URL.createObjectURL(event.target.files[0]);
    }

    function previewUpdateIcon(event, id) {
        document.getElementById("icon_preview_" + id).src =
            URL.createObjectURL(event.target.files[0]);
    }
</script>

@endsection
