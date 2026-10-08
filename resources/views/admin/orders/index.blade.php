@extends('admin.layouts.master')

@section('main_content')

<style>
    .eco-order-badge {
        padding: 0.4rem 0.9rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* ORDER STATUS */
    .eco-status-pending_payment { background:#fff3cd; color:#856404; }
    .eco-status-processing { background:#e3f2fd; color:#1565c0; }
    .eco-status-shipped { background:#ede7f6; color:#512da8; }
    .eco-status-cancelled { background:#ffebee; color:#c62828; }

    /* PAYMENT STATUS */
    .eco-payment-paid { background:#e8f5e9; color:#2e7d32; }
    .eco-payment-unpaid { background:#fff3cd; color:#856404; }
    .eco-payment-failed { background:#ffebee; color:#c62828; }
</style>

<div class="container-fluid my-4">

    <!-- Header -->
    <div class="eco-header-common eco-header-sm mb-4">
        <div class="eco-header-left">
            <i class="fa-solid fa-bag-shopping eco-header-icon"></i>
            <div>
                <h4 class="eco-header-title">Order Board</h4>
                <p class="eco-header-subtitle">
                    Manage and monitor all customer orders
                </p>
            </div>
        </div>

        <span class="eco-header-right badge badge-light px-3 py-2">
            Total : {{ $orders->total() }} Orders
        </span>
    </div>

    @include('components.partials.success-alert')
    @include('components.partials.error-alert')

    <div class="card eco-card">
        <div class="card-body">

            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" id="orderSearch" class="form-control"
                        placeholder="Search order number or user name...">
                </div>

                <div class="col-md-2">
                    <select id="statusFilter" class="form-control">
                        <option value="all">All Status</option>
                        <option value="pending_payment">Pending Payment</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select id="paymentFilter" class="form-control">
                        <option value="all">All Payments</option>
                        <option value="paid">Paid</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select id="sortFilter" class="form-control">
                        <option value="latest">Latest</option>
                        <option value="oldest">Oldest</option>
                        <option value="total_high">Total High</option>
                        <option value="total_low">Total Low</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button id="resetFilters" class="btn btn-outline-secondary w-100">
                        Reset
                    </button>
                </div>
            </div>

            <div id="ordersTableWrapper">
                @include('admin.orders.partials.table')
            </div>

        </div>
    </div>
</div>

<script>
    let searchTimeout = null;

    function loadOrders(url = null) {

        let search = document.getElementById("orderSearch").value;
        let status = document.getElementById("statusFilter").value;
        let payment = document.getElementById("paymentFilter").value;
        let sort = document.getElementById("sortFilter").value;

        let fetchUrl = url ?? "{{ route('admin.orders') }}";

        let finalUrl = new URL(fetchUrl, window.location.origin);

        finalUrl.searchParams.set("search", search);
        finalUrl.searchParams.set("status", status);
        finalUrl.searchParams.set("payment", payment);
        finalUrl.searchParams.set("sort", sort);

        fetch(finalUrl.toString(), {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById("ordersTableWrapper").innerHTML = html;
        });
    }

    document.addEventListener("DOMContentLoaded", () => {

        document.getElementById("orderSearch").addEventListener("keyup", () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadOrders(), 400);
        });

        document.getElementById("statusFilter").addEventListener("change", () => loadOrders());
        document.getElementById("paymentFilter").addEventListener("change", () => loadOrders());
        document.getElementById("sortFilter").addEventListener("change", () => loadOrders());

        document.getElementById("resetFilters").addEventListener("click", () => {
            document.getElementById("orderSearch").value = "";
            document.getElementById("statusFilter").value = "all";
            document.getElementById("paymentFilter").value = "all";
            document.getElementById("sortFilter").value = "latest";
            loadOrders();
        });

        // pagination click ajax
        document.addEventListener("click", function(e) {

            let link = e.target.closest(".eco-pagination a");

            if (link) {
                e.preventDefault();
                let url = link.getAttribute("href");
                loadOrders(url);
            }

        });

    });
</script>

@endsection
