@extends('admin.layouts.master')

@section('main_content')

<style>
    /* Enhanced Product Card Design */
    .product-card {
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid #eef2f7;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        border-color: #dbeafe;
    }

    /* Image Section */
    .product-img-container {
        height: 130px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #f1f5f9;
    }

    .product-img-container::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
    }

    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-img {
        transform: scale(1.03);
    }

    /* Eco badge */
    .eco-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background: #16a34a;
        color: white;
        font-size: 0.65rem;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: 700;
        z-index: 10;
    }

    /* Category */
    .product-category {
        font-size: 0.7rem;
        font-weight: 600;
        color: #1d4ed8;
        background: #eff6ff;
        padding: 2px 6px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 4px;
    }

    /* Status Badge (Optional - add if you want stock status) */
    .stock-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(255, 255, 255, 0.95);
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.65rem;
        font-weight: 600;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        z-index: 10;
    }

    .in-stock {
        color: #059669;
        background: rgba(5, 150, 105, 0.1);
    }

    .low-stock {
        color: #d97706;
        background: rgba(217, 119, 6, 0.1);
    }

    /* Image count badge */
    .image-count-badge {
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: rgba(15, 23, 42, 0.85);
        color: #ffffff;
        font-size: 0.65rem;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 3px;
        z-index: 10;
    }

    .image-count-badge i {
        font-size: 0.6rem;
    }

    /* Body Content */
    .product-body {
        padding: 12px 12px 10px;
        flex-grow: 1;
    }

    .product-title {
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 6px;
        color: #0f172a;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.2em;
    }

    .product-price {
        font-weight: 800;
        color: #047857;
        font-size: 1rem;
        margin-bottom: 4px;
        letter-spacing: -0.2px;
    }

    .product-usd {
        font-size: 0.75rem;
        color: #475569;
        margin-bottom: 4px;
    }

    .product-stock {
        font-size: 0.72rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .product-stock::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #94a3b8;
        display: inline-block;
    }

    /* Actions Section */
    .card-actions {
        padding: 10px 12px 12px;
        border-top: 1px solid #f1f5f9;
        background: #fafcff;
        margin-top: auto;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
    }

    .action-btn {
        font-size: 0.72rem;
        padding: 6px 0;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: white;
        transition: all 0.2s ease;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .view-btn {
        color: #059669;
        border-color: #d1fae5;
        background: #f0fdf4;
    }

    .view-btn:hover {
        background: #dcfce7;
        border-color: #86efac;
    }

    .edit-btn {
        color: #2563eb;
        border-color: #dbeafe;
        background: #eff6ff;
        text-decoration: none !important;
    }

    .edit-btn:hover {
        background: #dbeafe;
        border-color: #93c5fd;
    }

    .delete-btn {
        color: #dc2626;
        border-color: #fee2e2;
        background: #fef2f2;
    }

    .delete-btn:hover {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    /* Empty State Enhancement */
    .text-center .fa-box-open {
        opacity: 0.5;
        transition: transform 0.3s ease;
    }

    .text-center:hover .fa-box-open {
        transform: scale(1.1);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .product-img-container {
            height: 120px;
        }

        .action-btn {
            font-size: 0.7rem;
            padding: 5px 0;
        }
    }

    @media (max-width: 576px) {
        .action-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        /* Force delete button to span full row */
        .action-grid .delete-btn {
            grid-column: 1 / -1;
        }

        .action-btn {
            font-size: 0.65rem;
            padding: 6px 0;
        }
    }

    /* Loading animation for image placeholder */
    @keyframes shimmer {
        0% { background-position: -200px 0; }
        100% { background-position: 200px 0; }
    }

    .product-img-container:empty {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }

    /* ===== Eco Dropdown Style (Same as Category) ===== */
    .eco-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;

        background: #e8f5e9 url("data:image/svg+xml,%3Csvg fill='green' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5H7z'/%3E%3C/svg%3E")
            no-repeat right 0.75rem center / 1rem 1rem;

        padding-right: 2rem;
        border-radius: 50px;
        border: 1px solid #2e7d32;
        color: #2e7d32;
        font-weight: 500;
        cursor: pointer;
    }

    .eco-select:focus {
        border-color: #1b5e20;
        box-shadow: 0 0 0 0.15rem rgba(46,125,50,.2);
    }
</style>

<div class="container-fluid my-4">

    {{-- Original Header --}}
    <div class="eco-header-common eco-header-sm mb-4">
        <div class="eco-header-left">
            <i class="fa-solid fa-box eco-header-icon"></i>
            <div>
                <h4 class="eco-header-title">Product Management</h4>
                <p class="eco-header-subtitle">
                    Manage EcoShop products and inventory
                </p>
            </div>
        </div>

        <span class="eco-header-right badge badge-light px-3 py-2">
            Total : {{ $products->total() }} Products
        </span>
    </div>

    <!-- Alerts -->
    @include('components.partials.success-alert')
    @include('components.partials.error-alert')
    @include('components.partials.warning-alert')

    <!-- Product Controls -->
    <form action="{{ route('product#list') }}" method="GET" class="mb-4">
        <div class="row mb-4 align-items-center">
            <!-- Search -->
            <div class="col-md-4 col-sm-12 mb-3 mb-md-0">
                <div class="eco-search-wrapper">
                    <input
                        type="text"
                        name="search"
                        id="liveSearch"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Search products...">
                </div>
            </div>

            <!-- Category Filter -->
            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                <select name="category_id" id="liveCategory" class="form-control eco-select">
                    <option value="all">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort -->
            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                <select name="sort" class="form-control eco-select">
                    <option value="default">Sort by</option>

                    <option value="price_asc"  @selected(request('sort')=='price_asc')>Price: Low → High</option>
                    <option value="price_desc" @selected(request('sort')=='price_desc')>Price: High → Low</option>

                    <option value="name_asc"   @selected(request('sort')=='name_asc')>Name: A → Z</option>
                    <option value="name_desc"  @selected(request('sort')=='name_desc')>Name: Z → A</option>
                </select>
            </div>
        </div>
    </form>



    <div id="productGrid">
        @include('admin.product.partials.product_grid', ['products' => $products])
    </div>

    <div class="ajax-pagination" id="paginationArea">
        @include('admin.product.partials.pagination', ['products' => $products])
    </div>

    <div id="productModalsArea">
        @include('admin.product.partials.product_modals', ['products' => $products])
    </div>

    @include('admin.product.partials.delete_modal')

</div>

<script src="{{ asset('global/js/ajax-global.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    AjaxList({
        url: "{{ route('product#list') }}",
        params: {
            search: "#liveSearch",
            category_id: "#liveCategory",
            sort: "select[name='sort']"
        },
        targets: {
            html: "#productGrid",
            pagination: "#paginationArea",
            modals: "#productModalsArea"
        }
    });

});
</script>

<script>
document.addEventListener("click", function(e){

    const deleteBtn = e.target.closest(".js-delete");
    if (!deleteBtn) return;

    const name = deleteBtn.dataset.name;
    const url  = deleteBtn.dataset.url;

    const nameEl = document.getElementById("deleteProductName");
    const formEl = document.getElementById("deleteProductForm");

    if (!nameEl || !formEl) {
        console.error("Delete modal elements not found in DOM");
        return;
    }

    nameEl.textContent = name;
    formEl.action = url;

    openEcoModalById("ecoDeleteModal");
});
</script>

@endsection
