@extends('admin.layouts.master')

@section('main_content')

<div class="container-fluid my-4">

    <!-- Header -->
    <div class="eco-header-common eco-header-sm mb-4">

        <!-- Left: back button + title -->
        <div class="eco-header-left has-back">
            <a href="{{ route('admin.settings') }}"
            class="btn btn-light text-success rounded-circle">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div>
                <h5 class="eco-header-title mb-0">
                    <i class="fas fa-sliders-h mr-1"></i>
                    System Settings
                </h5>
                <p class="eco-header-subtitle">
                    Application configuration & branding
                </p>
            </div>
        </div>

        <!-- Right: SuperAdmin Badge -->
        <span class="eco-header-right badge badge-light px-3 py-2">
            <i class="fas fa-shield-alt"></i> Settings Panel
        </span>

    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            @include('components.partials.success-alert')
            @include('components.partials.error-alert')
            @include('components.partials.warning-alert')

            <form action="{{ route('admin.settings.system.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- LOGO ICON --}}
                    <div class="col-md-6 mb-3">
                        <div class="mt-2">
                            <img id="logoPreview"
                                src="{{ setting('logo')
                                    ? asset('storage/' . setting('logo'))
                                    : asset('default/logo.png') }}"
                                style="height:50px"
                            >
                        </div>
                        <label class="form-label">Site Logo (Navbar Icon)</label>
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" id="logoInput">

                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- LOGO WITH TEXT --}}
                    <div class="col-md-6 mb-3">
                        <div class="mt-2">
                            <img id="logoTextPreview"
                                src="{{ setting('logo_text')
                                    ? asset('storage/' . setting('logo_text'))
                                    : asset('default/logoText.png') }}"
                                style="height:50px"
                            >
                        </div>
                        <label class="form-label">Site Logo (Full Text Logo)</label>
                        <input type="file" name="logo_text" class="form-control @error('logo_text') is-invalid @enderror" id="logoTextInput">

                        @error('logo_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- FAVICON --}}
                    <div class="col-md-6 mb-3">
                        <div class="mt-2">
                            <img id="faviconPreview"
                                src="{{ setting('favicon')
                                    ? asset('storage/' . setting('favicon'))
                                    : asset('default/favicon.png') }}"
                                style="height:50px"
                            >
                        </div>
                        <label class="form-label">Favicon</label>
                        <input type="file" name="favicon" class="form-control @error('favicon') is-invalid @enderror" id="faviconInput">
                        <small class="text-muted">PNG / ICO (32×32)</small>

                        @error('favicon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="row">
                    {{-- APP NAME --}}
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <label class="form-label">APP Name</label>
                        <input type="text"
                            name="app_name"
                            class="form-control @error('app_name') is-invalid @enderror"
                            value="{{ old('app_name', setting('app_name')) }}">

                        @error('app_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SYSTEM EMAIL --}}
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <label class="form-label">System Email</label>
                        <input type="email"
                            name="app_email"
                            class="form-control @error('app_email') is-invalid @enderror"
                            value="{{ old('app_email', setting('contact_email')) }}">

                        @error('app_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SYSTEM PHONE --}}
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <label class="form-label">System Phone</label>
                        <input type="text"
                            name="app_phone"
                            class="form-control @error('app_phone') is-invalid @enderror"
                            value="{{ old('app_phone', setting('contact_phone')) }}">

                        @error('app_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ADDRESS --}}
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <label class="form-label">System Address</label>
                        <input type="text"
                            name="address"
                            class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address', setting('address', 'Hlaing Township, Yangon')) }}"
                            placeholder="Hlaing Township, Yangon">

                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-eco text-white my-3">
                    <i class="fas fa-save"></i> Update System Settings
                </button>

            </form>

        </div>
    </div>

</div>

<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById(previewId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('logoInput').addEventListener('change', function(){
        previewImage(this, 'logoPreview');
    });

    document.getElementById('logoTextInput').addEventListener('change', function(){
        previewImage(this, 'logoTextPreview');
    });

    document.getElementById('faviconInput').addEventListener('change', function(){
        previewImage(this, 'faviconPreview');
    });
</script>

@endsection
