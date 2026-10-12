@extends('admin.layouts.master')

@section('main_content')

<style>
.create-bar{
    display: flex;
    justify-content: space-between;
    align-items: flex-end; /* aligns button with select */
    gap: 16px;
}

.create-bar > div{
    flex: 1; /* make select take space */
}

.create-bar button{
    white-space: nowrap;
}

/* FIX bootstrap switch spacing */
.active-switch.form-switch{
    padding-left: 0;
}

.active-switch{
    display: flex;
    align-items: center;
    gap: 8px;
}

.active-switch .form-check-input{
    width: 3rem;
    height: 1.5rem;
    cursor: pointer;
    margin-left: 0;
    flex-shrink: 0;
}

.active-switch .form-check-label{
    margin: 0;
    user-select: none;
    white-space: nowrap;
}

/* MOBILE */
@media (max-width: 767.98px){
    .create-bar{
        flex-direction: column;   /* stack them */
        align-items: stretch;
    }

    .create-bar > div{
        width: 100%;
    }

    .active-switch{
        justify-content: center;
    }

    .create-bar button{
        width: 100%;
    }
}
</style>

<div class="container-fluid my-4">

    <!-- Header -->
    <div class="eco-header-common eco-header-sm mb-4">

        <!-- Left: back button + title -->
        <div class="eco-header-left">
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

        <!-- Right Badge -->
        <span class="eco-header-right badge badge-light px-3 py-2">
            <i class="fas fa-shield-alt"></i> Settings Panel
        </span>

    </div>

    {{-- Alerts --}}
    @include('components.partials.success-alert')
    @include('components.partials.error-alert')
    @include('components.partials.warning-alert')

    {{-- CREATE FORM --}}
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
                    <div class="col-md-6">
                        <label class="form-label fw-bold" for="eco-field-45b7d2-1">Name (English)</label>
                        <input type="text"
                            name="name_en"
                            value="{{ old('name_en') }}"
                            class="form-control @error('name_en') is-invalid @enderror"
                            required id="eco-field-45b7d2-1">

                        @error('name_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold" for="eco-field-45b7d2-2">Name (Myanmar)</label>
                        <input type="text"
                            name="name_mm"
                            value="{{ old('name_mm') }}"
                            class="form-control @error('name_mm') is-invalid @enderror" id="eco-field-45b7d2-2">

                        @error('name_mm')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold" for="eco-field-45b7d2-3">Type</label>
                        <select name="type"
                                class="form-select @error('type') is-invalid @enderror"
                                required id="eco-field-45b7d2-3">

                            <option value="manual" {{ old('type') == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="cod" {{ old('type') == 'cod' ? 'selected' : '' }}>Cash On Delivery</option>
                            <option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="e_wallet" {{ old('type') == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="stripe" {{ old('type') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                        </select>

                        @error('type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold" for="eco-field-45b7d2-4">Currency</label>
                        <select name="currency_code"
                                class="form-select @error('currency_code') is-invalid @enderror"
                                required id="eco-field-45b7d2-4">

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

                    <div class="col-md-6">
                        <label class="form-label fw-bold" for="eco-field-45b7d2-5">Account Name</label>
                        <input type="text"
                            name="account_name"
                            value="{{ old('account_name') }}"
                            class="form-control @error('account_name') is-invalid @enderror" id="eco-field-45b7d2-5">

                        @error('account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold" for="eco-field-45b7d2-6">Account Number</label>
                        <input type="text"
                            name="account_number"
                            value="{{ old('account_number') }}"
                            class="form-control @error('account_number') is-invalid @enderror" id="eco-field-45b7d2-6">

                        @error('account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="mt-2">
                            <img id="create_icon_preview"
                                src="{{ asset('storage/default/no-image.png') }}"
                                style="width:70px; height:70px; object-fit:cover; border-radius:12px; border:1px solid #ddd;">
                        </div>

                        <label class="form-label fw-bold" for="eco-field-45b7d2-7">
                            Payment Icon (Optional) (512x512)
                        </label>

                        <input type="file"
                            name="icon"
                            class="form-control @error('icon') is-invalid @enderror"
                            accept="image/png,image/webp,image/jpeg"
                            onchange="previewCreateIcon(event)" id="eco-field-45b7d2-7">

                        @error('icon')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="text-muted">
                            Upload icon must be exactly 512x512.
                        </small>
                    </div>

                    <div class="col-md-6">
                        <div class="mt-2">
                            <img id="create_qr_preview"
                                src="{{ asset('storage/default/no-image.png') }}"
                                style="width:70px; height:70px; object-fit:cover; border-radius:12px; border:1px solid #ddd;">
                        </div>

                        <label class="form-label fw-bold" for="eco-field-45b7d2-8">QR Image (Optional)</label>
                        <input type="file"
                            name="qr_image"
                            class="form-control @error('qr_image') is-invalid @enderror"
                            accept="image/png,image/webp,image/jpeg"
                            onchange="previewCreateQR(event)" id="eco-field-45b7d2-8">

                        @error('qr_image')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="create-bar mt-3 p-3 rounded"
                            style="background:#f8f9fa; border:1px solid #e5e5e5;">

                            <div>
                                <label class="form-label fw-bold" for="eco-field-45b7d2-9">Status</label>
                                <select name="is_active"
                                        class="form-select @error('is_active') is-invalid @enderror" id="eco-field-45b7d2-9">

                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>

                                @error('is_active')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-plus-circle"></i> Create Payment Method
                            </button>

                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- PAYMENT METHODS LIST --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light fw-bold">
            <i class="fas fa-list text-primary"></i> Payment Methods List
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th style="width: 30px;" class="text-center">ID</th>
                            <th style="width: 280px;">Name</th>
                            <th style="width: 40px;" class="text-center">Type</th>
                            <th style="width: 40px;" class="text-center">Currency</th>
                            <th style="width: 200px;" class="text-center">Account</th>
                            <th style="width: 120px;" class="text-center">Icon</th>
                            <th style="width: 120px;" class="text-center">QR</th>
                            <th style="width: 60px;" class="text-center">Status</th>
                            <th style="width: 150px;" class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($methods as $method)

                            <tr>
                                <td class="fw-bold text-center">
                                    {{ $loop->iteration }}
                                </td>

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
                                    <span class="badge bg-secondary">
                                        {{ $method->currency_code }}
                                    </span>
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
                                            style="width:70px; height:70px; object-fit:cover; border-radius:12px;">
                                    @else
                                        <span class="text-muted">No Icon</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($method->qr_image)
                                        <img src="{{ asset('storage/'.$method->qr_image) }}"
                                             style="width:80px; height:80px; object-fit:cover; border-radius:10px;">
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
                                            data-bs-toggle="modal"
                                            data-bs-target="#updateModal_{{ $method->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    <button class="btn btn-sm btn-danger w-100"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal_{{ $method->id }}">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>

                            @include('admin.settings.payment_methods.update')
                            @include('admin.settings.payment_methods.delete')

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

        </div>
    </div>

</div>

<script>
    function previewCreateQR(event){
        document.getElementById("create_qr_preview").src = URL.createObjectURL(event.target.files[0]);
    }

    function previewCreateIcon(event){
        document.getElementById("create_icon_preview").src = URL.createObjectURL(event.target.files[0]);
    }

    function previewUpdateQR(event, id){
        document.getElementById("qr_preview_" + id).src = URL.createObjectURL(event.target.files[0]);
    }

    function previewUpdateIcon(event, id){
        document.getElementById("icon_preview_" + id).src = URL.createObjectURL(event.target.files[0]);
    }
</script>

@endsection
