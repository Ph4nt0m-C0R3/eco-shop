@extends('admin.layouts.master')

@section('main_content')

<style>
    /* ===== Admin List Page ===== */

    .admin-table-card {
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .admin-table thead {
        background: #f4f8f6;
    }

    .admin-table th {
        border: none;
        font-weight: 700;
        color: #2e7d32;
    }

    .admin-table td {
        vertical-align: middle;
        border-top: 1px solid #eef3ef;
    }

    .role-badge {
        padding: 0.4rem 0.7rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .role-superadmin {
        background: #2e7d32;
        color: #fff;
    }

    .role-admin {
        background: #1976d2;
        color: #fff;
    }

    .action-btn {
        border-radius: 2rem;
        font-size: 0.8rem;
        padding: 0.35rem 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .protected-text {
        font-size: 0.85rem;
        color: #999;
        font-style: italic;
    }

    a.back-btn {
        text-decoration: none;
    }

    .eco-modal-overlay.show {
        display: flex;
    }

    .btn-eco {
        min-width: 180px;
        padding: 0.75rem 1.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        z-index: 999;
    }

    @media (max-width: 991.98px) {
        .eco-header-common {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .eco-header-left {
            width: 100%;
        }

        .eco-header-common .btn-eco {
            width: 100%;
            justify-content: center;
        }
    }

    /* ===== Responsive Switch ===== */
    .desktop-table { display: block; }
    .mobile-admin-list { display: none; }

    /* Tablet + Mobile */
    @media (max-width: 991.98px) {
        .desktop-table { display: none !important; }
        .mobile-admin-list { display: block; }
    }

    /* ===== Admin Card (Mobile + Tablet) ===== */
    .admin-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 14px;
        margin-bottom: 12px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .admin-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .admin-card-name {
        font-weight: 700;
        font-size: .95rem;
    }

    .admin-card-email {
        font-size: .8rem;
        color: #6b7280;
    }

    .admin-card-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 12px;
        font-size: .8rem;
    }

    .admin-card-label {
        font-size: .7rem;
        color: #2e7d32;
    }

    .admin-card-value {
        font-weight: 500;
    }

    .admin-card-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .admin-card-actions button {
        flex: 1;
    }

    /* ===== REMOVE HORIZONTAL SCROLL ===== */

    /* 1. Prevent global overflow */
    html, body {
        overflow-x: hidden;
    }

    /* 2. Fix Bootstrap row overflow */
    .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    /* 3. Fix container padding on small screens */
    @media (max-width: 991.98px) {
        .container-fluid {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
    }

    /* 4. Ensure cards never overflow */
    .admin-card {
        width: 100%;
        overflow: hidden;
    }

    /* 5. Fix long text (VERY IMPORTANT) */
    .admin-card-email,
    .admin-card-value {
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    /* 6. Fix grid overflow */
    .admin-card-body {
        grid-template-columns: 1fr 1fr;
    }

    /* Always keep 2 columns on mobile & tablet */
    @media (max-width: 991.98px) {
        .admin-card-body {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* 7. Buttons should not overflow */
    .admin-card-actions {
        flex-wrap: wrap;
    }

    .admin-card-actions button {
        width: 100%;
    }

    /* 8. Kill table scroll completely on mobile */
    @media (max-width: 991.98px) {
        .table-responsive {
            overflow-x: hidden !important;
        }
    }

</style>

<div class="container-fluid my-4">

    <!-- Header -->
    <div class="eco-header-common eco-header-sm mb-4">

        <!-- Left: back button + title -->
        <div class="eco-header-left has-back">
            <a href="{{ route('admin.settings') }}" class="btn btn-light text-success rounded-circle">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div>
                <h5 class="eco-header-title mb-0">
                    <i class="fas fa-users-cog mr-1"></i>
                    Admin Management
                </h5>
                <p class="eco-header-subtitle">Manage administrator accounts</p>
            </div>
        </div>

        <!-- Right: Keep New Admin button exactly as-is -->
        <a href="{{ route('admin.settings.admins.create.page') }}"
        class="btn btn-light btn-eco text-white">
            <i class="fas fa-user-plus"></i> New Admin
        </a>

    </div>

    <!-- Flash Message -->
    @include('components.partials.success-alert')
    @include('components.partials.error-alert')
    @include('components.partials.warning-alert')

    <!-- Table -->
    <div class="card admin-table-card">
        <div class="card-body p-0 table-responsive">

            <form id="filterForm" method="GET" class="row g-3 filter-form p-4">

                <div class="col-md-4">
                    <label class="form-label small text-muted">Search</label>
                    <input
                        type="text"
                        name="search"
                        id="adminSearchInput"
                        value="{{ request('search') }}"
                        class="form-control js-filter-input"
                        placeholder="Name or email">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <a href="{{ route('admin.settings.admins') }}"
                    class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>

            </form>

            <div class="table-responsive desktop-table">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th width="60" class="text-center">#</th>
                            <th width="180">Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th width="120" class="text-center">Registered</th>
                            <th width="50" class="text-center">Active</th>
                            <th width="100" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody id="adminTableBody">
                        @include('admin.settings.admins.partials.admin_table', ['admins' => $admins])
                    </tbody>

                </table>
            </div>

            <div class="mobile-admin-list p-3">

                @forelse($admins as $admin)
                    <div class="admin-card">

                        <!-- Head -->
                        <div class="admin-card-head">
                            <div>
                                <div class="admin-card-name">{{ $admin->name }}</div>
                                <div class="admin-card-email">{{ $admin->email }}</div>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="admin-card-body">
                            <!-- Row 1 -->
                            <div>
                                <div class="admin-card-label">Phone</div>
                                <div class="admin-card-value">
                                    {{ $admin->phone ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="admin-card-label">Registered Date</div>
                                <div class="admin-card-value">
                                    {{ $admin->created_at->format('d M Y') }}
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div>
                                <div class="admin-card-label">Address</div>
                                <div class="admin-card-value">
                                    {{ $admin->address ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="admin-card-label">Active</div>
                                <div class="admin-card-value">
                                    @if($admin->isOnline())
                                        <span class="badge bg-success">
                                            <i class="fas fa-circle"></i> Now
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="far fa-clock"></i>
                                            {{ $admin->lastOnline() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="admin-card-actions">
                            @if($admin->isOnline())
                                <span class="protected-text w-100 text-center">
                                    <i class="fas fa-lock"></i> Online
                                </span>
                            @else
                                <button class="btn btn-sm btn-danger js-remove-admin"
                                    data-id="{{ $admin->id }}"
                                    data-name="{{ $admin->name }}">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        No admins found
                    </div>
                @endforelse

            </div>

        </div>

        <div id="adminPaginationArea">
            @include('admin.settings.admins.partials.admin_pagination', ['admins' => $admins])
        </div>

    </div>

</div>

<!-- ===== Eco Remove Admin Modal ===== -->
<div class="eco-modal-overlay" id="ecoRemoveAdminModal">
    <div class="eco-modal">

        <!-- Header -->
        <div class="eco-modal-header">
            <h5 class="text-danger">
                <i class="fas fa-user-times mr-2"></i>
                Remove Admin
            </h5>
            <button class="eco-modal-close"
                    onclick="closeEcoModalById('ecoRemoveAdminModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="eco-modal-body text-center">
            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>

            <p class="mb-2">
                Are you sure you want to remove
            </p>

            <h6 class="font-weight-bold text-dark" id="removeAdminName">
                Admin Name
            </h6>

            <small class="text-muted d-block mt-2">
                This admin will lose all access immediately.
            </small>
        </div>

        <!-- Footer -->
        <div class="eco-modal-footer justify-content-center">
            <button
                type="button"
                class="btn btn-light rounded-pill px-4"
                onclick="closeEcoModalById('ecoRemoveAdminModal')">
                Cancel
            </button>

            <form id="removeAdminForm" method="POST">
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-danger rounded-pill px-4">
                    <i class="fas fa-user-times mr-1"></i>
                    Remove
                </button>
            </form>
        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const searchInput   = document.getElementById("adminSearchInput");
    const tableBody     = document.getElementById("adminTableBody");
    const pagination    = document.getElementById("adminPaginationArea");

    let typingTimer;
    const delay = 400;

    function fetchAdmins(url = null) {

        if (url instanceof Event) url = null;

        let params = new URLSearchParams();
        if (searchInput.value.trim() !== "") {
            params.append("search", searchInput.value.trim());
        }

        const fetchUrl = url
            ? url
            : "{{ route('admin.settings.admins') }}?" + params.toString();

        fetch(fetchUrl, { headers: { "X-Requested-With": "XMLHttpRequest" } })
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = data.table;
                pagination.innerHTML = data.pagination;

                // Rebind remove buttons
                bindRemoveAdminButtons();
            });
    }

    searchInput.addEventListener("input", function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(fetchAdmins, delay);
    });

    pagination.addEventListener("click", function (e) {
        const link = e.target.closest("a");
        if (!link || link.classList.contains("disabled")) return;
        e.preventDefault();
        fetchAdmins(link.href);
    });

    function bindRemoveAdminButtons() {
        document.querySelectorAll('.js-remove-admin').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const name = btn.dataset.name;
                document.getElementById('removeAdminName').textContent = name;
                document.getElementById('removeAdminForm').action = `/admin/settings/admins/${id}`;
                openEcoModalById('ecoRemoveAdminModal');
            });
        });
    }

    bindRemoveAdminButtons();

});
</script>

@endsection

