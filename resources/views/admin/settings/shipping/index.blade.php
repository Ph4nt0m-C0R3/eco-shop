@extends('admin.layouts.master')

@section('main_content')

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
                    <i class="fas fa-truck mr-1"></i>
                    Shipping Zones (Myanmar)
                </h5>
                <p class="eco-header-subtitle">
                    Manage shipping fees by location
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

            <div class="mb-4">
                <form action="{{ route('admin.settings.shipping.store') }}" method="POST" class="row g-2">
                    @csrf

                    <div class="col-md-5">
                        <input type="text"
                            name="name"
                            class="form-control"
                            placeholder="Location name (e.g. Magway)"
                            required>
                    </div>

                    <div class="col-md-4">
                        <input type="number"
                            name="fee_mmk"
                            step="500"
                            class="form-control"
                            placeholder="Fee (MMK)"
                            required>
                    </div>

                    <div class="col-md-2 d-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input"
                                type="checkbox"
                                name="is_active"
                                checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <button class="btn btn-success w-100">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">#</th>
                            <th>Location</th>
                            <th style="width: 200px;">Fee (MMK)</th>
                            <th style="width: 150px;">Status</th>
                            <th style="width: 200px;">Update</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($zones as $zone)
                        <tr>
                            <td>{{ $zone->id }}</td>
                            <td class="fw-bold">{{ $zone->name }}</td>

                            <td>{{ number_format($zone->fee_mmk) }}</td>

                            <td>
                                @if($zone->is_active)
                                    <span class="badge bg-success">ACTIVE</span>
                                @else
                                    <span class="badge bg-danger">INACTIVE</span>
                                @endif
                            </td>

                            <td>
                                <form action="{{ route('admin.settings.shipping.update',$zone->id) }}"
                                      method="POST" class="d-flex gap-2">
                                    @csrf
                                    @method('PUT')

                                    <input type="number" step="0.01"
                                           name="fee_mmk"
                                           value="{{ $zone->fee_mmk }}"
                                           class="form-control form-control-sm"
                                           required>

                                    <div class="form-check ms-2">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="is_active"
                                               {{ $zone->is_active ? 'checked' : '' }}>
                                    </div>

                                    <button class="btn btn-sm btn-primary">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                        @if($zones->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                No shipping zones found.
                            </td>
                        </tr>
                        @endif
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@endsection
