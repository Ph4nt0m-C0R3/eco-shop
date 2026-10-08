@extends('admin.layouts.master')

@section('main_content')

@php
    $userListRoute = auth()->user()->isSuperAdmin()
        ? route('admin.settings.users')
        : route('admin.users');

    $isSuperAdmin = auth()->user()->isSuperAdmin();

    $pageTitle = $isSuperAdmin
        ? 'User Management'
        : 'User Overview';

    $pageSubtitle = $isSuperAdmin
        ? 'View & remove registered users'
        : 'View registered users';
@endphp

<style>
    /* ===== User List Page (Admin Style) ===== */

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
        background: #607d8b;
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

    .eco-modal-overlay.show {
        display: flex;
    }

    .method-badge {
        padding: 0.35rem 0.7rem;
        border-radius: 1rem;
        font-size: 0.72rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .method-local {
        background: #607d8b;
        color: #fff;
    }

    .method-google {
        background: #db4437;
        color: #fff;
    }

    .method-github {
        background: #24292e;
        color: #fff;
    }

    .stat-card {
        border-radius: 1.2rem;
        padding: 1.4rem;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 40px rgba(0,0,0,0.25);
    }

    .stat-card i {
        font-size: 2rem;
        opacity: 0.9;
    }

    .stat-card h5 {
        margin: 0;
        font-weight: 800;
        font-size: 1.4rem;
    }

    .bg-local {
        background: linear-gradient(135deg, #607d8b, #455a64);
    }

    .bg-google {
        background: linear-gradient(135deg, #db4437, #b71c1c);
    }

    .bg-github {
        background: linear-gradient(135deg, #24292e, #000);
    }

    /* Space around filter bar */
    .filter-bar {
        margin: 20px;
        margin-top: 0;
        padding: 15px;
        padding-top: 0;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.05);
    }

    @media (max-width: 768px) {
        .eco-header-left > a.btn {
            top: 100%;
        }

        .eco-header-common {
            padding-bottom: 0;
            margin-bottom: 0;
        }
    }

    /* ===== Responsive Switch ===== */
    .desktop-table { display: block; }
    .mobile-user-list { display: none; }

    /* Tablet + Mobile */
    @media (max-width: 991.98px) {
        .desktop-table { display: none !important; }
        .mobile-user-list { display: block; }
    }

    /* ===== User Card ===== */
    .user-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 14px;
        margin-bottom: 12px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    /* Header layout with avatar */
    .user-card-head {
        display: grid;
        grid-template-columns: 50px 1fr;
        gap: 12px;
        align-items: center;
        margin-bottom: 10px;
    }

    /* Avatar */
    .user-avatar {
        grid-row: span 2;
    }

    .user-avatar img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* Info */
    .user-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .user-card-name {
        font-weight: 700;
        font-size: .95rem;
    }

    .user-card-email {
        font-size: .8rem;
        color: #6b7280;
    }

    .user-card-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 12px;
        font-size: .8rem;
    }

    .user-card-label {
        font-size: .7rem;
        color: #2e7d32;
    }

    .user-card-value {
        font-weight: 500;
    }

    .user-card-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .user-card-actions button {
        flex: 1;
    }

    /* REMOVE HORIZONTAL SCROLL */
    html, body {
        overflow-x: hidden;
    }

    .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    @media (max-width: 991.98px) {
        .container-fluid {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .table-responsive {
            overflow-x: hidden !important;
        }
    }

    /* Fix long text */
    .user-card-email,
    .user-card-value {
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    /* Always 2 columns on mobile + tablet */
    @media (max-width: 991.98px) {
        .user-card-body {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* Prevent overflow */
    .user-card-body > div {
        min-width: 0;
    }

    /* Button wrap */
    .user-card-actions {
        flex-wrap: wrap;
    }

    .user-card-actions button {
        width: 100%;
    }
</style>

<div class="container-fluid my-4">

    <!-- Header -->
    <div class="eco-header-common eco-header-sm mb-4 pb-3">

        <!-- Left: back button + title -->
        <div class="eco-header-left has-back">
            @auth
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.settings') }}"
                    class="btn btn-light text-success rounded-circle">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                @endif
            @endauth

            <div>
                <h5 class="eco-header-title mb-0">
                    <i class="fas fa-users mr-1"></i>
                    {{ $pageTitle }}
                </h5>

                <p class="eco-header-subtitle">
                    {{ $pageSubtitle }}
                </p>
            </div>
        </div>

        <!-- Right: intentionally empty (no create user) -->
        <span class="eco-header-right badge badge-light px-3 py-2">
            Total : {{ $totalUsers }} Users
        </span>

    </div>

    <!-- Flash Messages -->
    @include('components.partials.success-alert')
    @include('components.partials.error-alert')
    @include('components.partials.warning-alert')

        <!-- Table -->
        <div class="card admin-table-card">

            <div class="row g-3 mb-4 px-4 pt-4">
                <div class="col-md-4">
                    <div class="stat-card bg-local">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h5>{{ $counts['local'] }}</h5>
                            <small>Local Users</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card bg-google">
                        <i class="fab fa-google"></i>
                        <div>
                            <h5>{{ $counts['google'] }}</h5>
                            <small>Google Users</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card bg-github">
                        <i class="fab fa-github"></i>
                        <div>
                            <h5>{{ $counts['github'] }}</h5>
                            <small>GitHub Users</small>
                        </div>
                    </div>
                </div>
            </div>

            <form id="filterForm" method="GET" class="row g-3 mb-4 align-items-end filter-bar">

                <!-- Search -->
                <div class="col-md-4">
                    <label class="form-label small text-muted">Search</label>
                    <input
                        type="text"
                        name="search"
                        id="userSearchInput"
                        value="{{ request('search') }}"
                        class="form-control js-filter-input"
                        placeholder="Name or email">
                </div>

                <!-- Register Method -->
                <div class="col-md-3">
                    <label class="form-label small text-muted">Registered Via</label>
                    <select name="register_method" class="form-select js-filter-input">
                        <option value="">All</option>
                        <option value="local"  @selected(request('register_method') === 'local')>Local</option>
                        <option value="google" @selected(request('register_method') === 'google')>Google</option>
                        <option value="github" @selected(request('register_method') === 'github')>GitHub</option>
                    </select>
                </div>

                <!-- Reset only -->
                <div class="col-md-3 d-flex gap-2">
                    <a href="{{ $userListRoute }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>

            <div class="table-responsive admin-table-wrapper desktop-table">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th width="60" class="text-center">#</th>
                            <th width="60">Avatar</th>
                            <th width="180">Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th width="180" class="text-center">Registered</th>
                            <th width="100" class="text-center">Created</th>
                            <th width="50" class="text-center">Active</th>
                            @auth
                                @if(auth()->user()->isSuperAdmin())
                                    <th width="60" class="text-center">Action</th>
                                @endif
                            @endauth
                        </tr>
                    </thead>

                    <tbody id="userTableBody">
                        @include('admin.settings.users.partials.user_table', ['users' => $users])
                    </tbody>

                </table>
            </div>

            <div class="mobile-user-list p-3">
                @forelse($users as $user)
                    <div class="user-card">

                        <!-- Head -->
                        <div class="user-card-head">
                            <!-- Avatar -->
                            <div class="user-avatar">
                                <img src="{{ $user->avatar_url }}" alt="avatar">
                            </div>

                            <!-- Name + Email -->
                            <div class="user-info">
                                <div class="user-card-name">{{ $user->name }}</div>
                                <div class="user-card-email">{{ $user->email }}</div>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="user-card-body">
                            <!-- Row 1 -->
                            <div>
                                <div class="user-card-label">Phone</div>
                                <div class="user-card-value">
                                    {{ $user->phone ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="user-card-label">Registered Via</div>
                                <div class="user-card-value">
                                    @php $method = $user->provider ?? 'local'; @endphp

                                    <span>
                                        @if($method === 'google')
                                            <i class="fab fa-google"></i> Google
                                        @elseif($method === 'github')
                                            <i class="fab fa-github"></i> GitHub
                                        @else
                                            <i class="fas fa-envelope"></i> Local
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div>
                                <div class="user-card-label">Address</div>
                                <div class="user-card-value">
                                    {{ $user->address ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="user-card-label">Registered Date</div>
                                <div class="user-card-value">
                                    {{ $user->created_at->format('d M Y') }}
                                </div>
                            </div>

                            <div>
                                <div class="user-card-label">Active</div>
                                <div class="user-card-value">
                                    @if($user->isOnline())
                                        <span class="badge bg-success">
                                            <i class="fas fa-circle"></i> Now
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="far fa-clock"></i>
                                            {{ $user->lastOnline() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        @if($isSuperAdmin)
                        <div class="user-card-actions">
                            @if($user->isOnline())
                                <span class="protected-text w-100 text-center">
                                    <i class="fas fa-lock"></i> Online
                                </span>
                            @else
                                <button class="btn btn-sm btn-danger js-remove-user"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            @endif
                        </div>
                        @endif

                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        No users found
                    </div>
                @endforelse
            </div>

        </div>

        <div id="userPaginationArea">
            @include('admin.settings.users.partials.user_pagination', ['users' => $users])
        </div>

    </div>

</div>

<!-- ===== Eco Remove User Modal ===== -->
<div class="eco-modal-overlay" id="ecoRemoveUserModal">
    <div class="eco-modal">

        <!-- Header -->
        <div class="eco-modal-header">
            <h5 class="text-danger">
                <i class="fas fa-user-times mr-2"></i>
                Remove User
            </h5>
            <button class="eco-modal-close"
                    onclick="closeEcoModalById('ecoRemoveUserModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="eco-modal-body text-center">
            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>

            <p class="mb-2">
                Are you sure you want to remove
            </p>

            <h6 class="font-weight-bold text-dark" id="removeUserName">
                User Name
            </h6>

            <small class="text-muted d-block mt-2">
                This user will lose access immediately.
            </small>
        </div>

        <!-- Footer -->
        <div class="eco-modal-footer justify-content-center">
            <button
                type="button"
                class="btn btn-light rounded-pill px-4"
                onclick="closeEcoModalById('ecoRemoveUserModal')">
                Cancel
            </button>

            <form id="removeUserForm" method="POST">
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
document.addEventListener("click", function (e) {

    const btn = e.target.closest(".js-remove-user");
    if (!btn) return;

    const id   = btn.dataset.id;
    const name = btn.dataset.name;

    document.getElementById("removeUserName").textContent = name;
    document.getElementById("removeUserForm").action =
        `/admin/settings/users/${id}`;

    openEcoModalById("ecoRemoveUserModal");
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const searchInput   = document.getElementById("userSearchInput");
    const filterSelect  = document.querySelector("select[name='register_method']");
    const tableBody     = document.getElementById("userTableBody");
    const pagination    = document.getElementById("userPaginationArea");

    let typingTimer;
    const delay = 400;

    function fetchUsers(url = null) {

        // If an Event is passed accidentally, ignore it
        if (url instanceof Event) {
            url = null;
        }

        let params = new URLSearchParams();

        if (searchInput.value.trim() !== "") {
            params.append("search", searchInput.value.trim());
        }

        if (filterSelect.value !== "") {
            params.append("register_method", filterSelect.value);
        }

        const fetchUrl = url
            ? url
            : "{{ $userListRoute }}?" + params.toString();

        fetch(fetchUrl, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => res.json())
        .then(data => {
            tableBody.innerHTML = data.table;
            pagination.innerHTML = data.pagination;
        });
    }

    // Live search
    searchInput.addEventListener("input", function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(fetchUsers, delay);
    });

    // Filter change
    filterSelect.addEventListener("change", fetchUsers);

    // Pagination
    pagination.addEventListener("click", function (e) {
        const link = e.target.closest("a");
        if (!link || link.classList.contains("disabled")) return;

        e.preventDefault();
        fetchUsers(link.href);
    });

});
</script>


@endsection
