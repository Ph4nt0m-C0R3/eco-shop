<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
        <link rel="icon"
        type="image/png"
        href="{{ setting('favicon')
            ? asset('storage/' . setting('favicon'))
            : asset('default/favicon.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Eco Shop | Admin Dashboard</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('global/css/eco-common.css') }}">

    <style>

        /* =========================
        EcoShop Theme Override
        ========================= */

        :root {
            --eco-primary: #2e7d32;   /* eco green */
            --eco-secondary: #81c784;
            --eco-dark: #1b5e20;
        }

        /* Sidebar background */
        .bg-gradient-primary {
            background: linear-gradient(180deg, var(--eco-primary), var(--eco-dark)) !important;
        }

        input[type="password"]::-ms-reveal {
            display: none;
        }

        /* Sidebar brand */
        .sidebar-brand-text {
            width: 100px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Active & hover items */
        .sidebar .nav-item.active .nav-link,
        .sidebar .nav-item .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.12);
        }

        /* Icons color */
        .sidebar .nav-link i {
            color: #e8f5e9;
        }

        /* Topbar */
        .topbar {
            background-color: #ffffff !important;
            border-bottom: 2px solid var(--eco-secondary);
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--eco-primary);
            border-color: var(--eco-primary);
        }

        .btn-primary:hover {
            background-color: var(--eco-dark);
            border-color: var(--eco-dark);
        }

        /* Scroll to top */
        .scroll-to-top {
            background-color: var(--eco-primary);
        }

        /* ====== GLOBAL ====== */
        html, body {
            height: 100%;
            overflow: hidden; /* remove browser scroll */
        }

        /* ====== WRAPPER ====== */
        #wrapper {
            height: 100vh;
            overflow: hidden;
        }

        /* ====== SIDEBAR ====== */
        #accordionSidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;     /* internal scroll */
            overflow-x: hidden;
            z-index: 1000;

            /* hide scrollbar */
            scrollbar-width: none;        /* Firefox */
        }
        #accordionSidebar::-webkit-scrollbar {
            display: none;                /* Chrome/Safari */
        }

        /* ====== CONTENT WRAPPER ====== */
        #content-wrapper {
            margin-left: 14rem; /* SB Admin default sidebar width */
            height: 100vh;
            overflow: hidden;
        }

        /* ====== TOPBAR ====== */
        .topbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 14rem; /* same as sidebar width */
            z-index: 1020;
        }

        /* ====== MAIN CONTENT ====== */
        #content {
            margin-top: 4.375rem; /* topbar height */
            height: calc(100vh - 4.375rem);
            overflow-y: auto;
            overflow-x: hidden;

            /* hide scrollbar */
            scrollbar-width: none;
        }
        #content::-webkit-scrollbar {
            display: none;
        }

        /* ====== FOOTER ====== */
        body.sidebar-toggled .topbar {
            left: 6.5rem;
        }

        body.sidebar-toggled #content-wrapper {
            margin-left: 6.5rem;
        }

        /* ===== FIX SIDEBAR BRAND ===== */
        .sidebar-brand {
            position: sticky;
            top: 0;
            z-index: 1100;
            background: linear-gradient(180deg, var(--eco-primary), var(--eco-dark));
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        /* Add spacing so menu doesn't hide under brand */
        .sidebar .nav-item:first-child {
            margin-top: 0.5rem;
        }

        /* ===== MOBILE FIX (SB Admin logic) ===== */
        @media (max-width: 767.98px) {

            /* Sidebar OPEN → push content */
            #content-wrapper {
                margin-left: 6.5rem;
                width: calc(100vw - 6.5rem);
            }

            .topbar {
                left: 6.5rem;
                width: calc(100vw - 6.5rem);
            }

            /* Sidebar COLLAPSED → full width */
            body.sidebar-toggled #content-wrapper {
                margin-left: 0;
                width: 100vw;
            }

            body.sidebar-toggled .topbar {
                left: 0;
                width: 100vw;
            }

            .fa-bars {
                color: var(--eco-primary);
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body id="page-top">

    @include('partials.spinner')

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar">
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center py-3" href="{{ route('adminDashboard') }}">
                    <div class="sidebar-brand-icon">
                        <img
                            src="{{ setting('logo')
                                ? asset('storage/' . setting('logo'))
                                : asset('default/logo.png') }}"
                            alt="Site Logo"
                            style="width:40px; height:40px; object-fit:contain;"
                        >
                    </div>
                    <div class="display-flex align-items-center sidebar-brand-text mx-2">
                        EcoShop
                        <span>Admin</span>
                    </div>
                </a>

                <!-- Nav Item - Dashboard -->
                <li class="nav-item {{ request()->routeIs('adminDashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('adminDashboard') }}">
                        <i class="fas fa-fw fa-gauge-high"></i><span>Dashboard</span>
                    </a>
                </li>

                <!-- Nav Item - Category -->
                <li class="nav-item {{ request()->routeIs('category*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('category#list') }}">
                        <i class="fas fa-layer-group fa-sm fa-fw"></i><span></span><span>Category</span>
                    </a>
                </li>

                <!-- Nav Item - Add Item -->
                <li class="nav-item {{ request()->routeIs('product#create*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('product#create.page') }}">
                        <i class="fas fa-box-open fa-sm fa-fw"></i>
                        <span>Add Product</span>
                    </a>
                </li>

                <!-- Nav Item - Product Details -->
                <li class="nav-item {{ request()->routeIs('product#list', 'product#edit*', 'product#update*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('product#list') }}">
                        <i class="fas fa-circle-info fa-sm fa-fw"></i>
                        <span>Product Details</span>
                    </a>
                </li>

                <!-- Nav Item - Order Board -->
                <li class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.orders') }}">
                        <i class="fas fa-bag-shopping fa-sm fa-fw"></i>
                        <span>Order Board</span>
                    </a>
                </li>

                <!-- Nav Item - Sale Information -->
                <li class="nav-item {{ request()->routeIs('admin.sales*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.sales') }}">
                        <i class="fas fa-chart-line fa-sm fa-fw"></i><span>Sale Information</span>
                    </a>
                </li>

                <!-- Subscribers & Messages -->
                <li class="nav-item {{ request()->routeIs('admin.messages') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.messages') }}">
                        <i class="fas fa-envelope fa-sm fa-fw"></i>
                        <span>Subscribers & Messages</span>
                    </a>
                </li>

                @auth
                    @if(auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())
                        <li class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.users') }}">
                                <i class="fas fa-users fa-sm fa-fw"></i>
                                <span>View Users</span>
                            </a>
                        </li>
                    @endif
                @endauth

                {{-- Nav Item - Setting (SuperAdmin only) --}}
                @auth
                    @if(auth()->user()->isSuperAdmin())
                        <li class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.settings') }}">
                                <i class="fas fa-sliders fa-sm fa-fw"></i>
                                <span>Setting</span>
                            </a>
                        </li>
                    @endif
                @endauth

                <!-- Nav Item - Change Password -->
                @auth
                    @if(in_array(auth()->user()->role, ['admin','superadmin']))
                        <li class="nav-item">
                            <a class="nav-link"
                            href="#" id="openChangePassword">
                                <i class="fas fa-lock fa-sm fa-fw"></i>
                                <span>Change Password</span>
                            </a>
                        </li>
                    @endif
                @endauth

                <!-- Nav Item - Logout -->
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="post" id="adminLogoutFormSidebar">
                        @csrf
                        <span class="nav-link">
                            <button type="button"
                                    class="btn btn-link text-decoration-none p-0 text-white adminLogoutBtn">
                                <i class="fas fa-right-from-bracket fa-sm fa-fw"></i><span>Logout</span>
                            </button>
                        </span>
                    </form>
                </li>

                <br>
                {{-- Divider --}}
                <hr class="sidebar-divider d-none d-md-block">

                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>

            </ul>
        </div>
        <!-- End of Sidebar -->

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 d-flex align-items-center justify-content-center text-decoration-none" style="width: 2.5rem; height: 2.5rem; padding: 0;">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 text-gray-600 small">{{ auth()->user()->name }}</span>
                        <img class="img-profile rounded-circle"
                            src="{{ auth()->user()->avatar_url }}"
                            alt="Profile">

                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">

                        <a class="dropdown-item" href="{{ route('admin#profile') }}">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>

                        @if(auth()->user()->isSuperAdmin())
                            <a class="dropdown-item" href="{{ route('admin.settings') }}">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                Settings
                            </a>
                        @endif

                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST" class="dropdown-item p-0" id="adminLogoutFormTop">
                            @csrf
                            <button type="button" class="dropdown-item adminLogoutBtn">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </li>

            </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                @yield('main_content')

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Eco Shop {{ date('Y') }}</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    @include('admin.profile.change-password-modal')

    @include('admin.layouts.logout')

    <!--Bootstrap Original JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('admin/js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('admin/vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('admin/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('admin/js/demo/chart-pie-demo.js') }}"></script>
    <script src="{{ asset('global/js/eco-common.js') }}"></script>

    <script>
        (function () {
            const STORAGE_KEY = 'eco_sidebar_state';

            // Restore sidebar state on page load
            document.addEventListener('DOMContentLoaded', function () {
                const state = localStorage.getItem(STORAGE_KEY);

                if (state === 'collapsed') {
                    document.body.classList.add('sidebar-toggled');
                    document.querySelector('.sidebar')?.classList.add('toggled');
                }
            });

            // Save state when sidebar is toggled
            function saveSidebarState() {
                const isCollapsed = document.body.classList.contains('sidebar-toggled');
                localStorage.setItem(STORAGE_KEY, isCollapsed ? 'collapsed' : 'expanded');
            }

            // Desktop toggle
            document.getElementById('sidebarToggle')?.addEventListener('click', saveSidebarState);

            // Mobile toggle
            document.getElementById('sidebarToggleTop')?.addEventListener('click', saveSidebarState);
        })();
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('changePasswordModal');

        if (!modalEl) return;

        const bs5Modal = new bootstrap.Modal(modalEl);

        document.getElementById('openChangePassword')?.addEventListener('click', function (e) {
            e.preventDefault();
            bs5Modal.show();
        });

        // reopen modal on validation errors
        @if ($errors->has('old_password') ||
            $errors->has('password') ||
            $errors->has('password_confirmation'))
            bs5Modal.show();
        @endif
    });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const logoutButtons = document.querySelectorAll(".adminLogoutBtn");
            const confirmBtn = document.getElementById("confirmAdminLogout");

            const sidebarForm = document.getElementById("adminLogoutFormSidebar");
            const topForm = document.getElementById("adminLogoutFormTop");

            if (!logoutButtons.length || !confirmBtn) return;

            const logoutModal = new bootstrap.Modal(document.getElementById("adminLogoutModal"));

            let selectedForm = null;

            logoutButtons.forEach(btn => {
                btn.addEventListener("click", function () {

                    // detect which form this button belongs to
                    selectedForm = btn.closest("form");

                    logoutModal.show();
                });
            });

            confirmBtn.addEventListener("click", function () {
                if (selectedForm) {
                    selectedForm.submit();
                }
            });

        });
    </script>

</body>

</html>
