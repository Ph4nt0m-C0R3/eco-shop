@extends('admin.layouts.master')

@section('main_content')

@php
    $symbol = $currency === 'MMK' ? 'Ks.' : '$';
    $decimals = $currency === 'MMK' ? 0 : 2;
@endphp

<style>
/* ===== Summary Cards ===== */
.sales-card {
    border-radius: 1rem;
    transition: 0.3s ease;
}

.sales-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.08);
}

/* Responsive table */
.table-responsive {
    overflow-x: auto;
}

/* Mobile spacing */
@media (max-width: 768px) {
    .eco-header-common {
        gap: 1rem;
    }
}

.card-header {
    color: #1b5e20;
}

/* ===== HEADER ACTIONS CONTAINER (same as dashboard) ===== */
.dashboard-actions {
    display: flex;
    align-items: center;
    gap: 12px;
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

/* ===== Action Button ===== */
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

/* ===== Responsive Header Actions ===== */
@media (max-width: 768px) {

    .eco-header-common {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .dashboard-actions {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }

    .dashboard-actions .badge {
        width: 100%;
        text-align: center;
    }

    .eco-header-select {
        width: 100%;
    }

    .eco-header-btn {
        width: 100%;
        justify-content: center;
    }

    .dashboard-actions form {
        width: 100%;
    }
}
</style>

<div class="container-fluid my-4">

    <!-- ===== Header ===== -->
    <div class="eco-header-common eco-header-sm mb-4">
        <div class="eco-header-left">
            <i class="fas fa-chart-line eco-header-icon"></i>
            <div>
                <h4 class="eco-header-title">Sales Analytics</h4>
                <p class="eco-header-subtitle">
                    Overview of revenue, payment methods and performance
                </p>
            </div>
        </div>

        <div class="dashboard-actions d-flex flex-wrap">

            {{-- Date Range Badge --}}
            <span class="badge badge-light px-3 py-2">
                <i class="fas fa-calendar-alt"></i>
                {{ request('from') ?? 'All Time' }}
                -
                {{ request('to') ?? 'Now' }}
            </span>

            {{-- Currency Switch --}}
            <form method="GET">
                <input type="hidden" name="from" value="{{ request('from') }}">
                <input type="hidden" name="to" value="{{ request('to') }}">

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

            {{-- Generate Report (same style) --}}
            <a href="{{ route('admin.sales.report', [
                'from' => request('from'),
                'to' => request('to'),
                'currency' => $currency
            ]) }}"
            class="eco-header-btn no-spinner">
                <i class="fas fa-download"></i>
                Generate Report
            </a>

        </div>
    </div>

    <!-- ===== Date Filter ===== -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <form method="GET">
                <input type="hidden" name="currency" value="{{ $currency }}">

                <div class="row align-items-end">
                    <div class="col-md-4 mb-2">
                        <label>From</label>
                        <input type="date" name="from"
                               value="{{ request('from') }}"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>To</label>
                        <input type="date" name="to"
                               value="{{ request('to') }}"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-2">
                        <button class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Apply Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== Summary Cards ===== -->
    <div class="row">

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card sales-card shadow-sm border-left-primary h-100 p-3">
                <h6>Total Orders</h6>
                <h4>{{ $totalOrders }}</h4>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card sales-card shadow-sm border-left-success h-100 p-3">
                <h6>Paid Orders</h6>
                <h4>{{ $paidOrders }}</h4>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card sales-card shadow-sm border-left-danger h-100 p-3">
                <h6>Cancelled Orders</h6>
                <h4>{{ $cancelledOrders }}</h4>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card sales-card shadow-sm border-left-info h-100 p-3">
                <h6>Total Revenue</h6>
                <h4>
                    {{ number_format($totalSales, $decimals) }}
                    {{ $symbol }}
                </h4>
            </div>
        </div>

    </div>

    <!-- ===== Sales By Payment ===== -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white font-weight-bold">
            Sales By Payment Method
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Payment Method</th>
                        <th>Total Orders</th>
                        <th>Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesByPayment as $row)
                        <tr>
                            <td>{{ strtoupper($row->payment_method) }}</td>
                            <td>{{ $row->total_orders }}</td>
                            <td>
                                {{ number_format($row->total_amount, $decimals) }}
                                {{ $symbol }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                No data available
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== Monthly Chart ===== -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white font-weight-bold">
            Monthly Sales ({{ now()->year }})
        </div>
        <div class="card-body">
            <div style="height: 350px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

</div>

<script src="{{ asset('admin/vendor/chart.js/Chart.min.js') }}"></script>

<script>
    var ctx = document.getElementById("salesChart").getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: "Revenue",
                backgroundColor: "#4e73df",
                data: @json($monthlyData),
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + " {{ $symbol }}";
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem) {
                        return "Revenue: " +
                            tooltipItem.yLabel.toLocaleString() +
                            " {{ $symbol }}";
                    }
                }
            }
        }
    });
</script>

@endsection
