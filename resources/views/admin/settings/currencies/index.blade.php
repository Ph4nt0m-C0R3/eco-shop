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
                    <i class="fas fa-coins mr-1"></i>
                    Currency Exchange Rates
                </h5>
                <p class="eco-header-subtitle">
                    Manage currency rates and activate/deactivate currencies
                </p>
            </div>
        </div>

        <!-- Right Badge -->
        <span class="eco-header-right badge badge-light px-3 py-2">
            <i class="fas fa-shield-alt"></i> Settings Panel
        </span>

    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Alerts --}}
            @include('components.partials.success-alert')
            @include('components.partials.error-alert')
            @include('components.partials.warning-alert')

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th style="width: 120px;">Code</th>
                            <th style="width: 200px;">Rate</th>
                            <th style="width: 150px;">Base</th>
                            <th style="width: 150px;">Status</th>
                            <th style="width: 350px;">Update</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($currencies as $currency)

                            <tr>
                                <td class="fw-bold">
                                    {{ $currency->code }}
                                </td>

                                <td>
                                    @if($currency->is_base)
                                        <span class="badge bg-success">
                                            1 (Base Currency)
                                        </span>
                                    @else
                                        {{ $currency->rate }}
                                    @endif
                                </td>

                                <td>
                                    @if($currency->is_base)
                                        <span class="badge bg-primary">YES</span>
                                    @else
                                        <span class="badge bg-secondary">NO</span>
                                    @endif
                                </td>

                                <td>
                                    @if($currency->is_active)
                                        <span class="badge bg-success">ACTIVE</span>
                                    @else
                                        <span class="badge bg-danger">INACTIVE</span>
                                    @endif
                                </td>

                                <td>
                                    @if(!$currency->is_base)

                                        <button class="btn btn-sm btn-primary"
                                                onclick="openEcoModalById('updateCurrencyModal_{{ $currency->id }}')">
                                            <i class="fas fa-edit"></i> Update
                                        </button>

                                    @else
                                        <span class="text-muted">
                                            Base currency cannot be edited
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            @include('admin.settings.currencies.update')

                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No currencies found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@endsection
