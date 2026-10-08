@extends('admin.layouts.master')

@section('main_content')

<style>
    @media (max-width: 767.98px) {
        .update-btn {
            margin-top: 15px;
        }
    }
</style>

<div class="container-fluid my-4">

    <!-- Header -->
    <div class="eco-header-common eco-header-sm mb-4">
        <div class="eco-header-left has-back">
            <a href="{{ route('admin.settings') }}"
               class="btn btn-light text-success rounded-circle">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div>
                <h5 class="eco-header-title mb-0">
                    <i class="fas fa-percent mr-1"></i>
                    Tax Settings
                </h5>
                <p class="eco-header-subtitle">
                    Configure tax percentage applied to orders
                </p>
            </div>
        </div>

        <span class="eco-header-right badge badge-light px-3 py-2">
            <i class="fas fa-shield-alt"></i> Settings Panel
        </span>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Alerts --}}
            @include('components.partials.success-alert')
            @include('components.partials.error-alert')

            <form action="{{ route('admin.settings.tax.update') }}" method="POST">
                @csrf

                <div class="row align-items-end">

                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            Tax Percentage (%)
                        </label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               max="100"
                               name="percent"
                               value="{{ $tax->percent }}"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-4 update-btn">
                        <button class="btn btn-success w-80">
                            <i class="fas fa-save"></i> Update Tax
                        </button>
                    </div>

                </div>

                <small class="text-muted">
                    Example: 5 = 5% tax
                </small>
            </form>

        </div>
    </div>

</div>

@endsection
