@extends('admin.layouts.master')

@section('main_content')

<style>
    /* ===== Eco Category Page ===== */
    .eco-header {
        background: linear-gradient(135deg, #2e7d32, #1b5e20);
        color: #fff;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }

    .eco-search {
        position: relative;
    }

    .eco-search input {
        border-radius: 2rem;
        padding-left: 2.8rem;
        height: 45px;
    }

    .eco-search i {
        position: absolute;
        top: 50%;
        left: 18px;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .eco-search-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .eco-search-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #e8f5e9;
        color: #2e7d32;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .eco-search-wrapper input {
        border-radius: 2rem;
        height: 45px;
    }

    .eco-badge {
        padding: 0.45rem 0.9rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .eco-badge.active {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .eco-badge.inactive {
        background: #ffebee;
        color: #c62828;
    }

    .eco-btn {
        border-radius: 50%;
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .eco-float-btn {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #2e7d32;
        color: #fff;
        box-shadow: 0 12px 25px rgba(0,0,0,0.25);
        font-size: 1.4rem;
        z-index: 1050;
    }

    .eco-float-btn:hover {
        background: #1b5e20;
        color: #fff;
    }

    /* Center table headers */
    #categoryTable thead th {
        color: black;
        text-align: center;
        vertical-align: middle;
    }

    #categoryTable tbody td {
        text-align: center;
        vertical-align: middle;
    }

    /* Make "Action" header smaller and center icons */
    #categoryTable thead th:last-child {
        width: 180px;
        text-align: center;
        vertical-align: middle;
    }

    @keyframes ecoModalPop {
        from {
            transform: scale(0.9) translateY(20px);
            opacity: 0;
        }
        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    /* Body */
    .eco-modal-body {
        padding: 1.5rem;
    }

    .eco-modal-body .form-control:focus {
        border-color: #2e7d32;
        box-shadow: 0 0 0 0.15rem rgba(46, 125, 50, 0.2);
    }

    /* Footer */
    .eco-modal-footer {
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        border-top: 1px solid #e0f2e9;
    }

    /* Custom select for eco style */
    .eco-select {
        appearance: none;       /* remove default arrow */
        -webkit-appearance: none;
        -moz-appearance: none;
        background: #e8f5e9 url("data:image/svg+xml,%3Csvg fill='green' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5H7z'/%3E%3C/svg%3E") no-repeat right 0.75rem center / 1rem 1rem;
        padding-right: 2rem;
        border-radius: 50px; /* keep rounded-pill */
        border: 1px solid #2e7d32;
        color: #2e7d32;
        font-weight: 500;
        cursor: pointer;
    }
</style>

<div class="container-fluid my-4">

    <!-- Header -->
    <div class="eco-header-common eco-header-sm mb-4">
        <div class="eco-header-left">
            <i class="fa-solid fa-leaf eco-header-icon"></i>
            <div>

                @if(auth()->user()->isSuperAdmin())
                    <h4 class="eco-header-title">Category Management</h4>
                    <p class="eco-header-subtitle">
                        Organize and manage EcoShop product categories
                    </p>
                @else
                    <h4 class="eco-header-title">Categories</h4>
                    <p class="eco-header-subtitle">
                        View available product categories
                    </p>
                @endif

            </div>
        </div>

        <span class="eco-header-right badge badge-light px-3 py-2">
            Total : {{ $totalCategories }} Categories
        </span>
    </div>

    <!-- Alerts -->
    @include('components.partials.success-alert')
    @include('components.partials.error-alert')
    @include('components.partials.warning-alert')

    <!-- Category Card -->
    <div class="card eco-card">
        <div class="card-body">

            <!-- Controls -->
            <div class="row mb-4 align-items-center">
                <form method="GET" action="{{ route('category#list') }}" class="row mb-4 align-items-center">

                    <!-- Search -->
                    <div class="col-md-6 col-sm-12 mb-3 mb-md-0">
                        <div class="eco-search-wrapper">
                            <div class="eco-search-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <input
                                type="text"
                                name="search"
                                id="categoryLiveSearch"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Search categories..."
                            >
                        </div>
                    </div>

                </form>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="categoryTable">
                    <thead class="text-muted">
                        <tr>
                            <th>#</th>
                            <th class=" text-start" width="500">Category</th>
                            <th>Created</th>
                            @if(auth()->user()->isSuperAdmin())
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody id="categoryTableBody">
                        @include('admin.category.partials.category_table', ['categories' => $categories])
                    </tbody>

                </table>
            </div>

            <div class="ajax-pagination" id="categoryPaginationArea">
                @include('admin.category.partials.category_pagination', ['categories' => $categories])
            </div>

        </div>
    </div>
</div>

<!-- Floating Add Button -->
@if(auth()->user()->role === 'superadmin')
    <a href="javascript:void(0)"
    class="eco-float-btn d-flex align-items-center justify-content-center text-decoration-none"
    onclick="openEcoModalById('ecoModal')">
        <i class="fas fa-plus"></i>
    </a>
@endif

@if(auth()->user()->role === 'superadmin')
    @include('admin.category.modals.create')
    @include('admin.category.modals.edit')
    @include('admin.category.modals.delete')
@endif

<!-- ===== Search & Filter Script ===== -->
<script src="{{ asset('global/js/ajax-global.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    AjaxList({
        url: "{{ route('category#list') }}",
        params: {
            search: "#categoryLiveSearch",
        },
        targets: {
            table: "#categoryTableBody",
            pagination: "#categoryPaginationArea"
        }
    });

});
</script>

<script>
document.addEventListener("click", function(e){

    // =============================
    // DELETE BUTTON
    // =============================
    const deleteBtn = e.target.closest(".js-delete");

    if(deleteBtn){
        const id = deleteBtn.dataset.id;
        const name = deleteBtn.dataset.name;

        document.getElementById("deleteCategoryName").textContent = name;
        document.getElementById("deleteCategoryForm").action =
            `/admin/category/delete/${id}`;

        const params = new URLSearchParams(window.location.search);
        document.getElementById("deletePage").value = params.get("page") || 1;

        openEcoModalById("ecoDeleteModal");
    }


    // =============================
    // EDIT BUTTON
    // =============================
    const editBtn = e.target.closest(".js-edit");

    if(editBtn){
        document.getElementById("editCategoryForm").action =
            `/admin/category/update/${editBtn.dataset.id}`;

        document.getElementById("editCategoryName").value =
            editBtn.dataset.name;

        document.getElementById("editCategoryNameMm").value =
            editBtn.dataset.name_mm || '';

        document.getElementById("editCategoryDescription").value =
            editBtn.dataset.description;

        openEcoModalById("ecoEditModal");
    }

});
</script>

@if ($errors->hasBag('edit') && session('edit_id'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('editCategoryForm').action =
                `/admin/category/update/{{ session('edit_id') }}`;

            document.getElementById('editCategoryName').value =
                @json(old('name'));

            document.getElementById('editCategoryNameMm').value =
                @json(old('name_mm'));

            document.getElementById('editCategoryDescription').value =
                @json(old('description'));

            document.getElementById('ecoEditModal').classList.add('show');
        });
    </script>
@endif

@endsection
