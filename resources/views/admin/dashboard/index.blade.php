@extends('admin.layouts.master')

@section('main_content')

@php
    $symbol = $currency === 'MMK' ? 'Ks.' : '$';
    $decimals = $currency === 'MMK' ? 0 : 2;
@endphp

<style>
/* ===== HEADER ACTIONS CONTAINER ===== */
.dashboard-actions {
    display: flex;
    align-items: center;
    gap: 12px; /* controls distance between currency + button */
}

/* remove form spacing */
.dashboard-actions form {
    margin: 0;
}

/* ===== Currency Select ===== */
.eco-header-select {
    height: 36px;
    padding: 0 14px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: white;
    font-size: 13px;
    font-weight: 600;
    min-width: 110px;
    cursor: pointer;
    appearance: none;
    text-align: center;
    text-align-last: center;
}

/* focus */
.eco-header-select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(34,197,94,0.2);
}

/* ===== Report Button ===== */
.eco-header-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 36px;
    padding: 0 18px;
    border-radius: 10px;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: white;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 6px 18px rgba(34,197,94,0.25);
    transition: 0.2s;
    z-index: 500;
}

/* hover */
.eco-header-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 24px rgba(34,197,94,0.35);
    color: white;
}

.eco-header-btn:hover,
.eco-header-btn:focus,
.eco-header-btn:active,
.eco-header-btn:visited {
    text-decoration: none !important;
    color: white;
}

/* ===== MOBILE HEADER FIX ===== */
@media (max-width: 768px) {

    .eco-header-common {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .dashboard-actions {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }

    .dashboard-actions form {
        width: 100%;
    }

    .eco-header-select {
        width: 100%;
    }

    .eco-header-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<!-- Begin Page Content -->
<div class="container-fluid mt-4">

    {{-- ECO COMMON HEADER --}}
    <div class="eco-header-common eco-header-sm mb-4">

        {{-- LEFT SIDE --}}
        <div class="eco-header-left">
            <i class="fas fa-chart-line eco-header-icon"></i>

            <div>
                <h4 class="eco-header-title">Dashboard</h4>
                <p class="eco-header-subtitle">
                    Monitor sales, earnings and store performance
                </p>
            </div>
        </div>

        {{-- RIGHT SIDE ACTIONS --}}

        <div class="dashboard-actions d-flex flex-wrap">
            {{-- Currency Switch --}}
            <form method="GET" action="{{ route('adminDashboard') }}">
                <select name="currency"
                    class="form-control eco-header-select"
                    onchange="this.form.submit()">

                    <option value="USD" {{ $currency == 'USD' ? 'selected' : '' }}>
                        USD ($)
                    </option>

                    <option value="MMK" {{ $currency == 'MMK' ? 'selected' : '' }}>
                        MMK (Ks)
                    </option>
                </select>
            </form>

            {{-- Report Button --}}
            <a href="{{ route('admin.dashboard.report', ['currency' => $currency]) }}"
            class="eco-header-btn no-spinner">
                <i class="fas fa-download"></i>
                <span>Generate Report</span>
            </a>
        </div>

    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Revenue (Monthly)</div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($monthlyEarnings, $decimals) }} {{ $symbol }}
                            </div>

                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Revenue (Annual)</div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($annualEarnings, $decimals) }} {{ $symbol }}
                            </div>

                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">

                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        {{ $tasksPercent }}%
                                    </div>

                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">

                                        <div class="progress-bar bg-info"
                                            role="progressbar"
                                            style="width: {{ $tasksPercent }}%"
                                            aria-valuenow="{{ $tasksPercent }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Requests</div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $pendingRequests }}
                            </div>

                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- Monthly Tax --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Tax Collected (Monthly)
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($monthlyTax, $decimals) }} {{ $symbol }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly Shipping --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Shipping Collected (Monthly)
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($monthlyShipping, $decimals) }} {{ $symbol }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Annual Tax --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Tax Collected (Annual)
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($annualTax, $decimals) }} {{ $symbol }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Annual Shipping --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Shipping Collected (Annual)
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($annualShipping, $decimals) }} {{ $symbol }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        {{-- Monthly Gross --}}
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                        Gross Sales (Monthly)
                    </div>
                    <div class="h4 mb-0 font-weight-bold text-gray-900">
                        {{ number_format($monthlyGross, $decimals) }} {{ $symbol }}
                    </div>
                    <small class="text-muted">
                        Revenue + Tax + Shipping
                    </small>
                </div>
            </div>
        </div>

        {{-- Annual Gross --}}
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                        Gross Sales (Annual)
                    </div>
                    <div class="h4 mb-0 font-weight-bold text-gray-900">
                        {{ number_format($annualGross, $decimals) }} {{ $symbol }}
                    </div>
                    <small class="text-muted">
                        Revenue + Tax + Shipping
                    </small>
                </div>
            </div>
        </div>

    </div>

    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Gross Sales Overview</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>

                    @php
                        $pieColors = ['text-primary', 'text-success', 'text-info'];
                    @endphp

                    <div class="mt-4 text-center small">
                        @foreach($pieLabels as $index => $label)
                            <span class="mr-2">
                                <i class="fas fa-circle {{ $pieColors[$index] ?? 'text-secondary' }}"></i>
                                {{ strtoupper($label) }}
                            </span>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<script>
    window.areaChartLabels = @json($chartLabels);

    window.chartRevenue = @json($chartRevenue);
    window.chartTax = @json($chartTax);
    window.chartShipping = @json($chartShipping);
    window.chartGross = @json($chartGross);

    window.pieLabels = @json($pieLabels);
    window.pieData = @json($pieData);

    window.currencySymbol = "{{ $symbol }}";
    window.currencyCode = "{{ $currency }}";
    window.currencyDecimals = {{ $decimals }};
</script>

@endsection
