@extends('user.layouts.master')

@section('content')

<style>
    .orders-wrapper{
        background:#f7f8fc;
        min-height:100vh;
        padding:50px 0;
        margin-top:120px;
    }

    .orders-card{
        background:#fff;
        border-radius:16px;
        box-shadow:0 10px 35px rgba(0,0,0,0.06);
        padding:25px;
    }

    .orders-title{
        font-weight:800;
        font-size:20px;
        margin-bottom:20px;
    }

    .order-row{
        border:1px solid #eceef5;
        border-radius:14px;
        padding:18px;
        margin-bottom:15px;
        transition:.2s;
        background:#fff;
    }

    .order-row:hover{
        border-color:#6c63ff;
        background:#f4f3ff;
        transform:translateY(-2px);
    }

    .order-number{
        font-weight:800;
        font-size:15px;
        margin-bottom:6px;
    }

    .order-meta{
        font-size:13px;
        color:#666;
        margin-bottom:4px;
    }

    .order-total{
        font-weight:800;
        font-size:16px;
        color:#111;
    }

    .badge-status{
        font-size:12px;
        font-weight:700;
        padding:6px 12px;
        margin: 3px 0;
        border-radius:30px;
        display:inline-block;
    }

    .status-paid{ background:#d1fae5; color:#065f46; }
    .status-pending{ background:#fef3c7; color:#92400e; }
    .status-cancelled{ background:#fee2e2; color:#991b1b; }

    .status-confirmed { background:#e0f2fe; color:#075985; }
    .status-shipped { background:#ede9fe; color:#5b21b6; }
    .status-completed { background:#d1fae5; color:#065f46; }

    .status-failed { background:#fee2e2; color:#991b1b; }

    .btn-view{
        background:linear-gradient(90deg,#6c63ff,#5848e5);
        color:white;
        border:none;
        border-radius:12px;
        padding:10px 16px;
        font-weight:700;
        font-size:13px;
        text-decoration:none;

        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
    }

    .btn-view:hover{
        opacity:.9;
        color:white;
    }

    /* Fix View Details button */
    .btn-view{
        justify-content: center !important;
        align-items: center !important;
        text-align: center;
    }

    .total-actions{
        align-items: stretch;
    }

    .total-actions a{
        display:flex;
        justify-content:center;
        align-items:center;
    }

    .btn-cancel {
        padding:10px 14px;
        border-radius:12px;
        font-weight:700;
        font-size:13px;
    }

    /* Right column wrapper */
    .order-row > div:last-child {
        text-align: right;
    }

    /* Total + buttons container */
    .total-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }

    /* Make buttons same width on mobile */
    .total-actions a,
    .total-actions button,
    .total-actions form {
        width: 100%;
    }

    /* Make form behave like block */
    .total-actions form {
        display: block;
    }

    /* Desktop layout */
    @media (min-width: 768px) {

        .total-actions {
            flex-direction: row;
            justify-content: flex-end;
            align-items: center;
        }

        .total-actions a,
        .total-actions button,
        .total-actions form {
            width: auto;
        }
    }

    .eco-modal-overlay{
        position:fixed;
        inset:0;
        background:rgba(0,0,0,0.45);
        display:flex;
        justify-content:center;
        align-items:center;
        z-index:9999;
        opacity:0;
        visibility:hidden;
        transition:0.25s ease;
        padding:15px;
    }

    .eco-modal-overlay.show{
        opacity:1;
        visibility:visible;
    }

    .eco-modal-box{
        background:#fff;
        width:100%;
        padding:20px;
        border-radius:16px;
        box-shadow:0 15px 40px rgba(0,0,0,0.15);
        transform:translateY(15px);
        transition:0.25s ease;
    }

    .eco-modal-overlay.show .eco-modal-box{
        transform:translateY(0);
    }

    .order-filter-bar{
        display:flex;
        gap:12px;
        flex-wrap:wrap;
        align-items:center;
        background:#f7f8fc;
        padding:14px;
        border-radius:16px;
        border:1px solid #eceef5;
    }

    .eco-search-box{
        flex:1;
        min-width:220px;
        display:flex;
        align-items:center;
        gap:10px;
        background:white;
        border-radius:14px;
        padding:10px 14px;
        border:1px solid #eceef5;
        box-shadow:0 6px 18px rgba(0,0,0,0.04);
    }

    .eco-search-box i{
        color:#777;
        font-size:14px;
    }

    .eco-search-box input{
        border:none;
        outline:none;
        width:100%;
        font-weight:700;
        font-size:13px;
        background:transparent;
    }

    .eco-select{
        min-width:160px;
        border-radius:14px;
        border:1px solid #eceef5;
        padding:10px 12px;
        font-size:13px;
        font-weight:700;
        background:white;
        box-shadow:0 6px 18px rgba(0,0,0,0.04);
    }

    .eco-select:focus{
        border-color:#6c63ff;
        outline:none;
    }

    .eco-reset-btn{
        border-radius:14px;
        font-weight:800;
        padding:10px 14px;
    }

    @media(max-width:768px){
        .eco-select{
            flex:1;
            min-width:140px;
        }
    }

    .eco-pagination-wrapper{
        display:flex;
        justify-content:center;
        margin-top:25px;
    }

    .eco-pagination{
        display:flex;
        gap:10px;
        align-items:center;
        flex-wrap:wrap;
        padding:12px 16px;
        border-radius:18px;
        background:#f7f8fc;
        border:1px solid #eceef5;
        box-shadow:0 8px 22px rgba(0,0,0,0.05);
    }

    .eco-page-number{
        width:42px;
        height:42px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:14px;
        font-weight:800;
        font-size:13px;
        text-decoration:none;
        background:white;
        color:#444;
        border:1px solid #eceef5;
        transition:0.2s ease;
        box-shadow:0 6px 14px rgba(0,0,0,0.04);
    }

    .eco-page-number:hover{
        border-color:#6c63ff;
        background:#f4f3ff;
        transform:translateY(-2px);
        color:#4f46e5;
    }

    .eco-page-number.active{
        background:linear-gradient(90deg,#6c63ff,#5848e5);
        color:white;
        border:none;
        box-shadow:0 10px 25px rgba(108,99,255,0.35);
    }

    .eco-page-dots{
        font-weight:900;
        color:#777;
        padding:0 6px;
        font-size:14px;
    }

    .orders-count-badge{
        background:linear-gradient(90deg,#6c63ff,#5848e5);
        color:white;
        font-size:16px;
        font-weight:900;
        padding:5px 12px;
        border-radius:30px;
        box-shadow:0 8px 20px rgba(108,99,255,0.35);
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:35px;
    }

    /* ===============================
    MOBILE FIX (max-width: 425px)
    =================================*/
    @media (max-width: 425px) {

        /* Reduce page spacing */
        .orders-wrapper{
            padding:20px 0;
            margin-top:90px;
        }

        .orders-card{
            padding:16px;
        }

        /* Header stack */
        .orders-card > .d-flex.justify-content-between{
            flex-direction:column;
            align-items:flex-start !important;
            gap:12px;
        }

        .orders-card > .d-flex .btn{
            width:100%;
        }

        .orders-title{
            font-size:16px;
            flex-wrap:wrap;
            gap:6px;
        }

        .orders-count-badge{
            font-size:13px;
            padding:4px 10px;
        }

        /* FILTER BAR STACK */
        .order-filter-bar{
            flex-direction:column;
            align-items:stretch;
            gap:10px;
        }

        .eco-search-box{
            width:100%;
            min-width:unset;
        }

        .eco-select{
            width:100%;
            min-width:unset;
        }

        .eco-reset-btn{
            width:100%;
        }

        /* ORDER ROW STACK */
        .order-row{
            padding:14px;
        }

        .order-row > div{
            width:100%;
        }

        .order-row > div:last-child{
            text-align:left;
            margin-top:12px;
        }

        .total-actions{
            align-items:stretch;
        }

        .btn-view,
        .btn-cancel{
            width:100%;
        }

        /* Pagination shrink */
        .eco-pagination{
            padding:8px 10px;
            gap:6px;
        }

        .eco-page-number{
            width:36px;
            height:36px;
            font-size:12px;
        }

        .eco-page-dots{
            font-size:12px;
        }

        /* Modal spacing */
        .eco-modal-box{
            padding:16px;
        }

        /* Smaller status badge on mobile */
        .badge-status{
            font-size:11px;
            padding:4px 10px;
            border-radius:20px;
        }

    }
</style>

<div class="orders-wrapper">
    <div class="container">

        <div class="order-filter-bar mb-4">
            <div class="eco-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="orderSearch"
                    placeholder="{{ __('orders.search_placeholder') }}">
            </div>

            <select id="statusFilter" class="eco-select">
                <option value="all">{{ __('orders.all_status') }}</option>
                <option value="pending_payment">{{ __('orders.pending_payment') }}</option>
                <option value="processing">{{ __('orders.processing') }}</option>
                <option value="shipped">{{ __('orders.shipped') }}</option>
                <option value="cancelled">{{ __('orders.cancelled') }}</option>
            </select>

            <select id="paymentFilter" class="eco-select">
                <option value="all">{{ __('orders.all_payments') }}</option>
                <option value="paid">{{ __('orders.paid') }}</option>
                <option value="unpaid">{{ __('orders.unpaid') }}</option>
                <option value="failed">{{ __('orders.failed') }}</option>
            </select>

            <select id="sortFilter" class="eco-select">
                <option value="latest">{{ __('orders.latest') }}</option>
                <option value="oldest">{{ __('orders.oldest') }}</option>
            </select>

            <button id="resetFilters" class="btn btn-outline-secondary eco-reset-btn">
                <i class="fa-solid fa-rotate-left me-1"></i> {{ __('orders.reset') }}
            </button>
        </div>

        <div class="orders-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ redirect()->back()->getTargetUrl() }}" class="btn btn-outline-secondary" style="border-radius:12px;font-weight:700;">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    {{ __('orders.back') }}
                </a>

                <h4 class="orders-title mb-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-box me-2 text-primary"></i>
                    {{ __('orders.my_orders') }}

                    <span class="orders-count-badge">
                        {{ __('orders.total') }} {{ $totalOrders }}
                    </span>
                </h4>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-xmark me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($orders->count() == 0)
                <div class="alert alert-warning mb-0">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    {{ __('orders.no_orders') }}
                </div>
            @else

                <div id="ordersListWrapper">
                    @include('user.pages.orders.partials.list')
                </div>

            @endif

        </div>

    </div>
</div>

<!-- CANCEL ORDER CONFIRM MODAL -->
<div id="cancelOrderOverlay" class="eco-modal-overlay">

    <div class="eco-modal-box" style="max-width:450px; border-radius:18px;">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold mb-0">
                <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>
                {{ __('orders.cancel_order') }}
            </h5>

            <button type="button"
                    class="eco-modal-close"
                    onclick="closeEcoModalById('cancelOrderOverlay')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <p class="text-muted mb-3" style="font-size:14px;">
            {{ __('orders.cancel_confirm_text') }}
        </p>

        <div class="d-flex justify-content-end gap-2">

            <button type="button"
                    class="btn btn-outline-secondary"
                    style="border-radius:12px;font-weight:700;"
                    onclick="closeEcoModalById('cancelOrderOverlay')">
                {{ __('orders.keep_order') }}
            </button>

            <button type="button"
                    id="confirmCancelBtn"
                    class="btn btn-danger"
                    style="border-radius:12px;font-weight:700;">
                {{ __('orders.confirm_cancel') }}
            </button>

        </div>

    </div>
</div>

<script>
    let selectedCancelForm = null;

    function openCancelModal(button) {
        selectedCancelForm = button.closest("form");
        openEcoModalById("cancelOrderOverlay");
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("confirmCancelBtn").addEventListener("click", () => {
            if (selectedCancelForm) {
                selectedCancelForm.submit();
            }
        });
    });
</script>

<script>
    let searchTimeout = null;

    function loadOrders(url = null){

        let search = document.getElementById("orderSearch").value;
        let status = document.getElementById("statusFilter").value;
        let payment = document.getElementById("paymentFilter").value;
        let sort = document.getElementById("sortFilter").value;

        let fetchUrl = url ?? "{{ route('user.orders') }}";
        let finalUrl = new URL(fetchUrl, window.location.origin);

        finalUrl.searchParams.set("search", search);
        finalUrl.searchParams.set("status", status);
        finalUrl.searchParams.set("payment", payment);
        finalUrl.searchParams.set("sort", sort);

        fetch(finalUrl.toString(), {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById("ordersListWrapper").innerHTML = html;
        });
    }

    document.addEventListener("DOMContentLoaded", () => {

        document.getElementById("orderSearch").addEventListener("keyup", () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadOrders(), 350);
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

        // pagination ajax
        document.addEventListener("click", function(e){

            let link = e.target.closest(".eco-pagination a");

            if(link){
                e.preventDefault();
                loadOrders(link.getAttribute("href"));
            }

        });

    });
</script>

@endsection
