<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    @php
        $segments = request()->segments();
        array_shift($segments); // remove current locale
        $path = implode('/', $segments);
    @endphp

    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="auth-user" content="{{ auth()->check() ? '1' : '0' }}">
        <title>{{ setting('app_name', 'EcoShop') }}</title>

        <!-- Favicon -->
        <link rel="icon"
        type="image/png"
        href="{{ setting('favicon')
            ? asset('storage/' . setting('favicon'))
            : asset('default/favicon.png') }}">

        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Myanmar:wght@400;500;600;700&display=swap" rel="stylesheet">


        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="{{ asset('user/lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">
        <link href="{{ asset('user/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">


        <!-- Customized Bootstrap Stylesheet -->
        <link href="{{ asset('user/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="{{ asset('user/css/style.css') }}" rel="stylesheet">

        <!-- Custom Stylesheet -->
        <link href="{{ asset('global/css/eco-common.css') }}" rel="stylesheet">
        <link href="{{ asset('global/css/product-card.css') }}" rel="stylesheet">
    </head>

    <style>
        /* Default (English & others) */
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        }

        .topbar,
        .navbar,
        .navbar * {
            line-height: 1.5;
        }

        .topbar small,
        .topbar a,
        .navbar .nav-link,
        .navbar .dropdown-item,
        .navbar .btn {
            line-height: 1.5;
        }

        /* ===============================
        GLOBAL MYANMAR TYPOGRAPHY FIX
        ================================ */

        html[lang="mm"] body {
            font-family: 'Noto Sans Myanmar', 'Pyidaungsu', sans-serif;
            font-size: 1rem;          /* DO NOT shrink Burmese */
            line-height: 1.9;         /* Global breathing room */
        }

        /* Paragraphs & lists */
        html[lang="mm"] p,
        html[lang="mm"] li,
        html[lang="mm"] label {
            font-size: 1rem;
            line-height: 1.95;
        }

        /* Links & small text */
        html[lang="mm"] a,
        html[lang="mm"] small {
            line-height: 1.7;
        }

        /* Headings */
        html[lang="mm"] h1,
        html[lang="mm"] h2,
        html[lang="mm"] h3,
        html[lang="mm"] h4,
        html[lang="mm"] h5,
        html[lang="mm"] h6 {
            line-height: 1.5;
            font-weight: 700;
            letter-spacing: 0;
        }

        /* Heading scale (balanced for MM) */
        html[lang="mm"] h1 { font-size: 2.4rem; }
        html[lang="mm"] h2 { font-size: 1.9rem; }
        html[lang="mm"] h3 { font-size: 1.5rem; }
        html[lang="mm"] h4 { font-size: 1.25rem; }
        html[lang="mm"] h5 { font-size: 1.1rem; }

        /* Buttons */
        html[lang="mm"] .btn {
            font-size: 1rem;
            line-height: 1.7;
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
        }

        /* Navbar */
        html[lang="mm"] .navbar .nav-link,
        html[lang="mm"] .dropdown-item {
            font-size: 1rem;
            line-height: 1.9;
        }

        /* Dropdown spacing */
        html[lang="mm"] .dropdown-menu .dropdown-item {
            padding-top: 0.65rem;
            padding-bottom: 0.65rem;
        }

        @media (max-width: 768px){

            html[lang="mm"] body {
                font-size: 0.98rem;
            }

            html[lang="mm"] h1 { font-size: 2rem; }
            html[lang="mm"] h2 { font-size: 1.6rem; }
            html[lang="mm"] h3 { font-size: 1.3rem; }

            html[lang="mm"] .navbar .nav-link {
                font-size: 1.05rem;
                line-height: 2;
            }

        }

        /* ======================================
        FOOTER — MYANMAR TYPOGRAPHY ALIGNMENT
        ====================================== */

        html[lang="mm"] .footer {
            line-height: 1.95;
        }

        /* Footer section titles */
        html[lang="mm"] .footer h4 {
            font-weight: 700;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        /* Footer description paragraph */
        html[lang="mm"] .footer p {
            font-size: 1rem;
            line-height: 2;
        }

        /* Footer links */
        html[lang="mm"] .footer .btn-link {
            font-size: 1rem;
            line-height: 2;
            margin-bottom: 6px;
        }

        html[lang="mm"] .footer form {
            position: relative;
        }

        html[lang="mm"] .footer button.btn {
            white-space: nowrap;
            padding-left: 20px;
            padding-right: 20px;
        }

        /* Contact block spacing */
        html[lang="mm"] .footer strong {
            font-weight: 700;
        }

        /* Subscribe input alignment */
        html[lang="mm"] .footer input.form-control {
            font-size: 1rem;
            line-height: 1.8;
            padding-top: 0.9rem;
            padding-bottom: 0.9rem;
        }

        /* Subscribe button */
        html[lang="mm"] .footer button.btn {
            font-size: 1rem;
            line-height: 1.8;
        }

        /* Payment icons breathing */
        html[lang="mm"] .footer .d-flex.flex-wrap {
            margin-top: 10px;
            gap: 10px !important;
        }

        /* Copyright text */
        html[lang="mm"] .copyright span {
            line-height: 1.9;
        }

        input[type="password"]::-ms-reveal {
            display: none;
        }

        .navbar-nav .nav-item {
            position: relative;
        }

        .navbar-nav .nav-link.dropdown-toggle {
            display: flex;
            align-items: center; /* center text + caret */
            padding-bottom: 0.5rem;
        }

        /* HERO FIX (Navbar Safe Area) */
        .eco-hero {
            margin-top: 150px;
            padding-top: 120px; /* FIX navbar overlap */
            padding-bottom: 70px;
            background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
        }

        .eco-hero h1 {
            font-weight: 700;
            color: #2e7d32;
        }

        .eco-subtitle {
            color: #6c757d;
            font-size: 0.95rem;
        }

        /* ICON */
        .eco-icon {
            font-size: 3rem;
            color: #4caf50;
            margin-bottom: 10px;
        }

        /* CONTENT */
        .eco-page-content {
            padding: 60px 0;
        }

        .eco-card {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .eco-card h5 {
            color: #2e7d32;
            margin-bottom: 12px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .eco-hero {
                padding-top: 100px;
                padding-bottom: 50px;
            }

            .eco-icon {
                font-size: 2.4rem;
            }
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e0e0;
        }

        .lang-dropdown {
            position: relative;
            font-size: 14px;
        }

        .lang-current {
            display: flex;
            align-items: center;
            gap: 6px;
            height: 38px;
            padding: 6px 12px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .lang-current img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }

        .lang-current i {
            font-size: 10px;
            margin-left: 2px;
        }

        html[lang="mm"] .lang-current span, .lang-item span {
            margin-top: 0.25rem;
        }

        html[lang="en"] .lang-item span {
            margin-top: 0;
        }

        /* Dropdown */
        .lang-menu {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
            min-width: 120px;
            overflow: hidden;
            opacity: 0;
            transform: translateY(8px);
            pointer-events: none;
            transition: all 0.2s ease;
            z-index: 1000;
        }

        .lang-dropdown.open .lang-menu {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .lang-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            text-decoration: none;
            color: #000;
            font-weight: 600;
            background: #fff;
        }

        .lang-item img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }

        .lang-item:hover {
            background: #f1f1f1;
        }

        .lang-item.active {
            background: #ffc107;
        }

        .lang-current i {
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .lang-dropdown.open .lang-current i {
            transform: rotate(180deg);
        }

        /* PROFILE DROPDOWN — CUSTOM STYLE */
        .profile-dropdown {
            position: relative;
            font-size: 14px;
        }

        /* Toggle button */
        .profile-current {
            display: flex;
            align-items: center;
            height: 42px;
            padding: 6px 14px;
            padding-left: 0;
            background: #ffffff;
            border: none;
            border-radius: 999px; /* pill */
            cursor: pointer;
            font-weight: 600;
        }

        .profile-current i {
            font-size: 10px;
            transition: transform 0.3s ease;
        }

        /* Dropdown menu */
        .profile-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.15);
            min-width: 220px;
            padding: 6px 0;
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
            transition: all 0.25s ease;
            z-index: 1000;
        }

        /* Open state */
        .profile-dropdown.open .profile-menu {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .profile-dropdown.open .profile-current i {
            transform: rotate(180deg);
        }

        /* Items */
        .profile-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 18px;
            text-decoration: none;
            color: #333;
            background: transparent;
            font-weight: 600;
            border: none;
            width: 100%;
        }

        .profile-item i {
            width: 18px;
            text-align: center;
            color: #6c757d;
        }

        .profile-item:hover {
            border-radius: 14px;
            background: #f1f3f5;
        }

        /* Logout emphasis */
        .profile-item.text-danger i {
            color: #dc3545;
        }

        /* Disable Bootstrap click behavior for Pages only */
        .pages-dropdown > .dropdown-toggle::after {
            transition: transform 0.3s ease;
        }

        .pages-dropdown:hover > .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        /* DESKTOP ONLY (>=1200px) */
        @media (min-width:1200px){

            .pages-dropdown .dropdown-menu{
                position:absolute;
                top:100%;
                left:0;
                min-width:240px;
                max-width:300px;

                opacity:0;
                visibility:hidden;
                transform:translateY(10px);
                transition:all 0.25s ease;
                display:block;
                pointer-events:none;
            }

            .pages-dropdown:hover .dropdown-menu{
                opacity:1;
                visibility:visible;
                transform:translateY(0);
                pointer-events:auto;
            }
        }

        /* =========================
        RIGHT SIDE NAV (OFFCANVAS)
        ========================= */

        /* Default desktop: normal */
        @media (min-width: 1200px) {
            .eco-offcanvas {
                display: flex !important;
                flex-grow: 1;
            }
        }

        /* Mobile + Tablet */
        @media (max-width: 1199px) {

            .eco-offcanvas {
                position: fixed;
                top: 0;
                right: -100%;
                width: 320px;
                max-width: 85%;
                height: 100vh;
                background: #ffffff;
                z-index: 1050;
                box-shadow: -10px 0 30px rgba(0,0,0,0.15);
                padding: 80px 20px 20px;
                transition: right 0.35s ease;
                overflow-y: auto;
            }

            .eco-offcanvas.show {
                right: 0;
            }

            /* Stack menu vertically */
            .eco-offcanvas .navbar-nav {
                flex-direction: column;
                align-items: stretch;
                margin-left: 0 !important;
            }

            .eco-offcanvas .nav-link {
                padding: 12px 0;
                font-size: 1.05rem;
                border-bottom: 1px solid #eee;
            }

            /* Dark overlay */
            .eco-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.4);
                z-index: 1040;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }

            .eco-overlay.show {
                opacity: 1;
                pointer-events: auto;
            }

            .eco-offcanvas .right-gp {
                flex-direction: row;   /* same row */
                align-items: center;
                justify-content: flex-start;
                gap: 10px;
                margin-top: 20px;
                padding: 0 10px;
            }

            .eco-floating-cart{
                position: fixed;
                right: 16px;
                bottom: 80px;
                z-index: 999;

                width: 56px;
                height: 56px;
                border-radius: 50%;

                background: #fff;
                box-shadow: 0 8px 20px rgba(0,0,0,.2);

                display: none;
                align-items: center;
                justify-content: center;
            }

            .eco-floating-cart.show{
                display: flex;
            }

            .eco-floating-cart i{
                font-size: 1.5rem;
            }

            .eco-floating-cart span{
                position: absolute;
                top: -6px;
                right: -6px;
                width: 22px;
                height: 22px;
                border-radius: 50%;
                background: #ffc107;
                color: #000;
                font-size: 12px;
                font-weight: 600;

                display: flex;
                align-items: center;
                justify-content: center;
            }

            .eco-floating-cart {
                animation: pulse 1.5s infinite;
            }

            @keyframes pulse {
                0% { box-shadow: 0 0 0 0 rgba(40,167,69,0.5); }
                70% { box-shadow: 0 0 0 12px rgba(40,167,69,0); }
                100% { box-shadow: 0 0 0 0 rgba(40,167,69,0); }
            }

        }

        /* Hide floating cart by default */
        .eco-floating-cart {
            display: none;
        }

        /* Only allow on mobile */
        @media (max-width: 768px) {
            .eco-floating-cart.show {
                display: flex;
            }
        }

        /* Hide scrollbar but keep scroll */
        .eco-offcanvas {
            overflow-y: auto;
            scrollbar-width: none; /* Firefox */
        }

        /* DESKTOP: allow floating dropdowns */
        @media (min-width:1200px){
            .eco-offcanvas{
                overflow: visible !important;
            }
        }

        .eco-offcanvas::-webkit-scrollbar {
            display: none; /* Chrome / Safari */
        }

        /* dropdown should stack below link */
        @media (max-width:1199px){
            .eco-offcanvas .dropdown-menu {
                position: static;
                transform: none !important;
                width: 100%;
                margin-top: 6px;
                border-radius: 8px;
            }

            .eco-offcanvas .right-gp{
                flex-direction: row;
                align-items: flex-start;
                gap: 12px;
                margin-top: 20px;
            }

            .lang-dropdown,
            .profile-dropdown{
                width: 100%;
            }

            .lang-current,
            .profile-current{
                width: 100%;
                justify-content: space-between;
            }
        }

        .eco-offcanvas .dropdown-menu {
            border: none;
            background: #f8f9fa;
            padding: 6px 10px;
            animation: ecoDropdownFade 0.25s ease;
        }

        /* FIX Myanmar dropdown text overflow */
        .eco-offcanvas .dropdown-menu .dropdown-item {
            white-space: normal;
            word-break: break-word;
            overflow-wrap: anywhere;
            line-height: 1.7;
        }

        @keyframes ecoDropdownFade {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .eco-offcanvas .dropdown-menu {
            max-width: 100%;
            white-space: normal;
        }

        .eco-close-btn {
            position: absolute;
            top:18px;
            right:18px;
            width:42px;
            height:42px;
            border-radius:50%;
            border:none;
            background:#ffffff;
            box-shadow:0 6px 18px rgba(0,0,0,0.12);
            font-size:18px;
            display:flex;
            align-items:center;
            justify-content:center;
            cursor:pointer;
            transition:all 0.25s ease;
        }

        /* icon color */
        .eco-close-btn i {
            color:#495057;
            transition:all 0.25s ease;
        }

        /* hover */
        .eco-close-btn:hover {
            background:#81c408;
            transform:rotate(90deg) scale(1.05);
            box-shadow:0 8px 22px rgba(0,0,0,0.18);
        }

        .eco-close-btn:hover i {
            color:#fff;
        }

        /* click press */
        .eco-close-btn:active {
            transform:scale(0.9);
        }

        .back-to-top {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 999;

            display: none;
            width: 45px;
            height: 45px;

            align-items: center;
            justify-content: center;
        }

    </style>

    <body>

        <!-- Spinner Start -->
        @include('partials.spinner')
        <!-- Spinner End -->


        <!-- Navbar start -->
        <div class="container-fluid fixed-top">
            <div class="container topbar bg-primary d-none d-lg-block">
                <div class="d-flex justify-content-between">
                    <div class="top-info ps-2">
                        <small class="me-3">
                            <i class="fas fa-map-marker-alt me-2 text-secondary"></i>
                            <a href="#" class="text-white">
                                {{ setting('address', 'Hlaing Township, Yangon') }}
                            </a>
                        </small>

                        <small class="me-3">
                            <i class="fas fa-envelope me-2 text-secondary"></i>
                            <a href="mailto:{{ setting('contact_email', 'hello@example.test') }}" class="text-white">
                                {{ setting('contact_email', 'hello@example.test') }}
                            </a>
                        </small>
                    </div>
                    <div class="top-link pe-2">
                        <a href="{{ route('privacy.policy') }}" class="text-white"><small class="text-white mx-2">{{ __('privacy_policy') }}</small>/</a>
                        <a href="{{ route('terms.use') }}" class="text-white"><small class="text-white mx-2">{{ __('terms_of_use') }}</small>/</a>
                        <a href="{{ route('sales.refunds') }}" class="text-white"><small class="text-white ms-2">{{ __('sales_refunds') }}</small></a>
                    </div>
                </div>
            </div>
            <div class="container px-0">
                <nav class="navbar navbar-light bg-white navbar-expand-xl">
                    <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
                        <img src="{{ setting('logo_text')
                                ? asset('storage/' . setting('logo_text'))
                                : asset('default/logoText.png') }}"
                            alt="logo text"
                            style="max-height:35px;">
                    </a>

                    <button class="navbar-toggler py-2 px-3" type="button" id="ecoNavToggle">
                        <span class="fa fa-bars text-primary"></span>
                    </button>

                    <div class="eco-offcanvas" id="navbarCollapse">
                        <button class="eco-close-btn d-xl-none" id="ecoNavClose">
                            <i class="fas fa-times"></i>
                        </button>

                        <div class="navbar-nav ms-3">
                            <a href="{{ route('home.products') }}"
                            class="nav-item nav-link {{
                                request()->routeIs('home.products', 'userHome','user.*') || request()->is('pages/*') ? 'active' : ''
                            }}">
                                {{ __('home') }}
                            </a>

                            <a href="{{ route('shop.index') }}"
                            class="nav-item nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}">
                                {{ __('shop') }}
                            </a>

                            <div class="nav-item dropdown pages-dropdown">
                                <a href="#" class="nav-link dropdown-toggle"
                                data-bs-toggle="dropdown"
                                data-bs-auto-close="outside">
                                    {{ __('pages') }}
                                </a>

                                <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                    <a href="{{ route('about.us') }}" class="dropdown-item">
                                        {{ __('about_us') }}
                                    </a>

                                    <a href="{{ route('why.us') }}" class="dropdown-item">
                                        {{ __('footer.why_title') ?? 'Why People Like Us' }}
                                    </a>

                                    <a href="{{ route('faqs') }}" class="dropdown-item">
                                        {{ __('faqs_help') }}
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <a href="{{ route('privacy.policy') }}" class="dropdown-item">
                                        {{ __('privacy_policy') }}
                                    </a>

                                    <a href="{{ route('terms.use') }}" class="dropdown-item">
                                        {{ __('terms_of_use') }}
                                    </a>

                                    <a href="{{ route('sales.refunds') }}" class="dropdown-item">
                                        {{ __('sales_refunds') }}
                                    </a>
                                </div>
                            </div>

                            <a href="{{ route('contact.us') }}" class="nav-item nav-link {{ request()->routeIs('contact.us') ? 'active' : '' }}">{{__('contact_us')}}</a>

                            @auth
                            <a href="{{ route('user.wishlist') }}"
                            class="nav-item nav-link d-xl-none">
                                {{ __('wishlist') }}
                            </a>
                            @endauth
                        </div>
                        <div class="d-flex ms-auto align-items-center right-gp">

                            {{-- Cart (DESKTOP ONLY) --}}
                            <a href="{{ route('user.cart') }}"
                            class="position-relative me-3 my-auto d-none d-xl-inline">

                                <i class="fa fa-shopping-cart fa-2x"></i>

                                @php
                                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('qty');
                                @endphp

                                <span id="ecoDesktopCartBadge"
                                    style="
                                        position:absolute;
                                        top:-6px;
                                        right:-6px;
                                        background:#ffc107;
                                        color:#000;
                                        width:22px;
                                        height:22px;
                                        border-radius:50%;
                                        font-size:12px;
                                        font-weight:600;
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;"
                                    class="{{ $cartCount > 0 ? '' : 'd-none' }}">
                                    {{ $cartCount }}
                                </span>
                            </a>

                            {{-- Wishlist --}}
                            <a href="{{ route('user.wishlist') }}" class="position-relative me-3 my-auto d-none d-xl-inline">
                                <i class="fa fa-heart fa-2x"></i>
                            </a>

                            {{-- Language Switch --}}
                            <div class="lang-dropdown me-3">
                                <button class="lang-current" type="button">
                                    <img
                                        src="{{ app()->getLocale() === 'mm'
                                            ? 'https://wavemoney.com.mm/wp-content/themes/wave-money/polylang/mm.svg'
                                            : 'https://wavemoney.com.mm/wp-content/themes/wave-money/polylang/en.svg' }}"
                                        alt="Lang"
                                    >
                                    <span>{{ strtoupper(app()->getLocale()) }}</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>

                                <div class="lang-menu">
                                    <a href="{{ route('lang.switch', 'en') }}"
                                    class="lang-item {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                                        <img src="https://wavemoney.com.mm/wp-content/themes/wave-money/polylang/en.svg">
                                        <span>EN</span>
                                    </a>

                                    <a href="{{ route('lang.switch', 'mm') }}"
                                    class="lang-item {{ app()->getLocale() === 'mm' ? 'active' : '' }}">
                                        <img src="https://wavemoney.com.mm/wp-content/themes/wave-money/polylang/mm.svg">
                                        <span>MM</span>
                                    </a>
                                </div>
                            </div>

                            {{-- Auth Actions --}}
                            @guest
                                <a href="{{ route('login') }}" class="my-auto">
                                    <i class="fas fa-user fa-2x"></i>
                                </a>
                            @endguest

                            @auth
                                <div class="profile-dropdown my-auto">
                                    <button class="profile-current" type="button">
                                        <span class="fw-semibold nav-name">
                                            {{ Str::of(auth()->user()->name ?: auth()->user()->nickname)->explode(' ')->first() }}
                                        </span>

                                        <img
                                            src="{{ auth()->user()->avatar_url }}"
                                            alt="Avatar"
                                            class="avatar ms-2"
                                            referrerpolicy="no-referrer"
                                        >

                                        <i class="fas fa-chevron-down ms-2"></i>
                                    </button>

                                    <div class="profile-menu">

                                        <a href="{{ route('user.profile') }}" class="profile-item">
                                            <i class="fas fa-user me-2"></i> {{ app()->getLocale() === 'mm' ? 'ပရိုဖိုင်' : 'Profile' }}
                                        </a>

                                        <a href="{{ route('user.orders') }}" class="profile-item">
                                            <i class="fas fa-box me-2"></i> {{ app()->getLocale() === 'mm' ? 'အော်ဒါများ' : 'Orders' }}
                                        </a>

                                        <div class="dropdown-divider"></div>

                                        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                            @csrf
                                            <button type="button"
                                                    class="profile-item text-danger w-100 text-start"
                                                    id="logoutBtn">
                                                <i class="fas fa-sign-out-alt me-2"></i> {{ app()->getLocale() === 'mm' ? 'အကောင့်ထွက်ရန်' : 'Logout' }}
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            @endauth

                        </div>
                    </div>
                </nav>

                <div class="eco-overlay" id="ecoOverlay"></div>

            </div>
        </div>
        <!-- Navbar End -->

        @yield('content')

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer">
            <div class="container py-5">
                <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                    <div class="row g-4">
                        <div class="col-lg-3">
                            <a href="#">
                                <h1 class="text-primary mb-0">{{ setting('app_name', 'EcoShop') }}</h1>
                                <p class="text-secondary mb-0">{{ __('footer.tagline') }}</p>
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="position-relative mx-auto">
                                @csrf

                                <input
                                    name="email"
                                    type="email"
                                    required
                                    class="form-control border-0 w-100 py-3 px-4 rounded-pill"
                                    placeholder="{{ __('footer.subscribe_placeholder') }}"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-primary border-0 border-secondary py-3 px-4 position-absolute rounded-pill text-white"
                                    style="top:0; right:0;">
                                    {{ __('footer.subscribe_btn') }}
                                </button>
                            </form>
                        </div>
                        <div class="col-lg-3">
                            <div class="d-flex justify-content-end pt-3">
                                <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                                <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">{{ __('footer.why_title') }}</h4>
                            <p class="mb-4">
                                {{ __('footer.why_desc') }}
                            </p>
                            <a href="{{ route('why.us') }}"
                            class="btn border-secondary py-2 px-4 rounded-pill text-primary">
                                {{ __('footer.why_btn') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <h4 class="text-light mb-3">{{ __('footer.shop_info') }}</h4>
                            <a class="btn-link" href="{{ route('about.us') }}">{{ __('about_us') }}</a>
                            <a class="btn-link" href="{{ route('contact.us') }}">{{ __('contact_us') }}</a>
                            <a class="btn-link" href="{{ route('privacy.policy') }}">{{__('privacy_policy')}}</a>
                            <a class="btn-link" href="{{ route('terms.use') }}">{{ __('terms_of_use') }}</a>
                            <a class="btn-link" href="{{ route('sales.refunds') }}">{{__('sales_refunds')}}</a>
                            <a class="btn-link" href="{{ route('faqs') }}">{{ __('faqs_help') }}</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <h4 class="text-light mb-3">{{ __('footer.account') }}</h4>

                            @guest
                                <a class="btn-link" href="{{ route('login') }}">{{ __('footer.my_account') }}</a>
                                <a class="btn-link" href="{{ route('login') }}">{{ __('footer.cart') }}</a>
                                <a class="btn-link" href="{{ route('login') }}">{{ __('footer.wishlist') }}</a>
                                <a class="btn-link" href="{{ route('login') }}">{{ __('footer.orders') }}</a>
                            @endguest

                            @auth
                                <a class="btn-link" href="{{ route('user.profile') }}">{{ __('footer.my_account') }}</a>

                                <a class="btn-link" href="{{ route('user.cart') }}">{{ __('footer.cart') }}</a>

                                <a class="btn-link" href="{{ route('user.wishlist') }}">{{ __('footer.wishlist') }}</a>

                                <a class="btn-link" href="{{ route('user.orders') }}">{{ __('footer.orders') }}</a>
                            @endauth
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">{{ __('footer.contact') }}</h4>
                            <p>
                                <strong>{{ __('footer.address') }} :</strong>
                                {{ setting('address', 'Hlaing Township, Yangon') }}
                            </p>

                            <p>
                                <strong>{{ __('footer.email') }} :</strong>
                                {{ setting('contact_email', 'hello@example.test') }}
                            </p>

                            <p>
                                <strong>{{ __('footer.phone') }} :</strong>
                                {{ setting('contact_phone', '000-000-0000') }}
                            </p>

                            <p>{{ __('footer.payment') }}</p>

                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                @foreach($footerPaymentMethods as $method)
                                    <img
                                        src="{{ asset('storage/' . $method->icon) }}"
                                        alt="{{ $method->display_name }}"
                                        title="{{ $method->display_name }}"
                                        style="width:30px; height:30px; object-fit:contain;"
                                    >
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Copyright Start -->
        <div class="container-fluid copyright bg-dark py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <span class="text-light">
                                <a href="#">
                                    <i class="fas fa-copyright text-light me-2"></i>
                                    {{ setting('app_name', 'EcoShop') }} {{ date('Y') }}
                                </a>, {{ __('footer.copyright') }}
                        </span>
                    </div>
                    <div class="col-md-6 my-auto text-center text-md-end text-white">
                        <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                        <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                        <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                        Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a> Distributed By <a class="border-bottom" href="https://themewagon.com">ThemeWagon</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright End -->

        {{-- Floating Cart --}}
        @php
        $cartCount = auth()->check()
            ? \App\Models\Cart::where('user_id', auth()->id())->sum('qty')
            : 0;
        @endphp

        @php
        $hideFloatingCart = request()->routeIs(
            'user.cart*',
            'user.checkout*',
            'checkout.*',

            'login',
            'register',
            'password.*',

            'user.profile*',
            'user.wishlist*',

            'pages.*',
            'about.us',
            'contact.us',
            'privacy.policy',
            'terms.use',
            'sales.refunds',
            'faqs',
            'why.us'
        );
        @endphp

        @if(!$hideFloatingCart)
            <a href="{{ route('user.cart') }}"
            id="ecoCartLink"
            class="eco-floating-cart {{ $cartCount > 0 ? 'show' : '' }}">

                <i class="fas fa-shopping-cart"></i>

                <span id="ecoCartBadge"
                    class="{{ $cartCount > 0 ? '' : 'd-none' }}">
                    {{ $cartCount }}
                </span>
            </a>
        @endif

        <!-- Back to Top -->
        <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

        @include('user.layouts.logout')

    <script>
        function updateNavbarHeight() {
            const fixedNav = document.querySelector('.container-fluid.fixed-top');
            if (!fixedNav) return;

            document.documentElement.style.setProperty(
                '--navbar-height',
                fixedNav.offsetHeight + 'px'
            );
        }

        // Run on:
        window.addEventListener("load", updateNavbarHeight);      // after everything loads
        window.addEventListener("resize", updateNavbarHeight);    // when window resizes
        window.addEventListener("orientationchange", updateNavbarHeight); // mobile rotate

        // Extra safety: if fonts or language change height
        const observer = new ResizeObserver(updateNavbarHeight);
        const nav = document.querySelector('.container-fluid.fixed-top');
        if (nav) observer.observe(nav);
    </script>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('user/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('user/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('user/lib/lightbox/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('user/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('user/js/main.js') }}"></script>

    <!-- Custom Javascript -->
    <script src="{{ asset('global/js/eco-common.js') }}"></script>

    @include('user.layouts.lang-js')
    <script src="{{ asset('user/js/global-toast.js') }}"></script>

    {{-- Newsletter Toast Messages --}}
    @if(session('subscribe_success'))
    <script>
    window.addEventListener("load", function () {
        showToast("{{ __('Subscribed successfully') }}", "success");
    });
    </script>
    @endif

    @if(session('subscribe_error') === 'invalid')
    <script>
    window.addEventListener("load", function () {
        showToast("{{ __('Invalid email address') }}", "error");
    });
    </script>
    @endif

    @if(session('subscribe_error') === 'exists')
    <script>
    window.addEventListener("load", function () {
        showToast("{{ __('Email already subscribed') }}", "warning");
    });
    </script>
    @endif

    {{-- CONTACT TOAST --}}
    @if(session('contact_success'))
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        showToast(window.LANG.contact_success, 'success');
    });
    </script>
    @endif

    @if(session('contact_error'))
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        showToast(@json(session('contact_error')), 'error');
    });
    </script>
    @endif

    <script src="{{ asset('user/js/wishlist.js') }}"></script>
    <script src="{{ asset('user/js/cart.js') }}"></script>

    <script>
        function updateFloatingCart(){
            const cart = document.getElementById("ecoCartLink");
            const badge = document.getElementById("ecoCartBadge");
            if(!cart || !badge) return;

            const hasItems = !badge.classList.contains("d-none");
            const isMobile = window.innerWidth <= 768;

            if(hasItems && isMobile){
                cart.classList.add("show");
            }else{
                cart.classList.remove("show");
            }
        }

        window.addEventListener("load", updateFloatingCart);
        window.addEventListener("resize", updateFloatingCart);
        document.addEventListener("cartUpdated", updateFloatingCart);
    </script>

    <script>
        document.addEventListener('click', function (e) {

            if (e.target.closest('.eco-wishlist-btn')) return;

            document.querySelectorAll('.lang-dropdown, .profile-dropdown').forEach(dropdown => {
                const button = dropdown.querySelector('button');

                if (button && button.contains(e.target)) {
                    dropdown.classList.toggle('open');
                    return;
                }

                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });
        });
    </script>

    <script>
        const toggleBtn = document.getElementById('ecoNavToggle');
        const closeBtn = document.getElementById('ecoNavClose');
        const panel = document.getElementById('navbarCollapse');
        const overlay = document.getElementById('ecoOverlay');

        function closeNav(){
            panel.classList.remove('show');
            overlay.classList.remove('show');
        }

        toggleBtn.addEventListener('click', () => {
            panel.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        closeBtn.addEventListener('click', closeNav);
        overlay.addEventListener('click', closeNav);
    </script>

    <script>
        window.addEventListener("scroll", function () {
            const cart = document.getElementById("ecoCartLink");
            const backToTop = document.querySelector(".back-to-top");

            if (!backToTop) return;

            if (window.scrollY > 300) {
                backToTop.style.display = "flex";
            } else {
                backToTop.style.display = "none";
            }

            // Important: remove any inline display override
            if (cart) {
                cart.style.display = "";
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const logoutBtn = document.getElementById("logoutBtn");
            const logoutForm = document.getElementById("logoutForm");
            const confirmLogoutBtn = document.getElementById("confirmLogoutBtn");

            if (!logoutBtn || !logoutForm || !confirmLogoutBtn) return;

            const logoutModal = new bootstrap.Modal(document.getElementById("logoutConfirmModal"));

            logoutBtn.addEventListener("click", function () {
                logoutModal.show();
            });

            confirmLogoutBtn.addEventListener("click", function () {
                logoutForm.submit();
            });
        });
    </script>

    </body>
</html>
