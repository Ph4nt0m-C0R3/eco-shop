@extends('user.layouts.master')

@section('content')

    <link href="{{ asset('global/css/product-card.css') }}" rel="stylesheet">

    <style>
        .container, .container-fluid, .container-xxl, .container-xl, .container-lg, .container-md, .container-sm {
            padding-right: var(--bs-gutter-x, 1rem);
            padding-left: var(--bs-gutter-x, 1rem);
        }

        /* =========================
        GRID (SHOP = 3 PER ROW)
        ========================= */
        .eco-products-grid{
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 25px;
            width: 100%;
        }

        .eco-product-card{
            width: 100%;
            max-width: 100%;
            min-width: 0;
        }

        /* Tablet */
        @media (max-width: 992px){
            .eco-products-grid{
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        /* Mobile */
        @media (max-width: 576px){
            .eco-products-grid{
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
        }

        /* =========================
        ECO PAGINATION (FLAT THEME)
        ========================= */
        .eco-pagination-wrapper{
            display: flex;
            justify-content: center;
            margin-top: 35px;
        }

        .eco-pagination{
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Page Numbers */
        .eco-page-number{
            min-width: 44px;
            height: 42px;
            padding: 0 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px; /* NOT circle */
            border: 2px solid #81c408;
            color: #333;
            background: #fff;
            font-weight: 600;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        /* Hover effect (NO gradient, nice flat) */
        .eco-page-number:hover{
            background: #ff9800;
            color: #fff;
            border-color: #81c408;
        }

        /* Active page */
        .eco-page-number.active{
            background: #81c408;
            border-color: #ff9800;
            color: #fff;
        }

        /* Dots (...) */
        .eco-pagination span{
            font-weight: 700;
            color: #777;
            padding: 0 6px;
        }

        /* Mobile */
        @media (max-width: 576px){
            .eco-pagination{
                gap: 6px;
                padding: 10px 12px;
            }

            .eco-page-number{
                width: 38px;
                height: 38px;
                font-size: 0.9rem;
            }
        }

        /* =========================
        SHOP FILTER UI (ECO THEME)
        ========================= */
        .eco-filter-card{
            background: #fff;
            border: 1px solid #e8f5e9;
            border-radius: 16px;
            padding: 18px 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        }

        .eco-filter-title{
            font-size: 1.1rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .eco-filter-title i{
            color: #81c408;
        }

        /* ===== Search Bar ===== */
        .eco-search-box{
            display: flex;
            align-items: center;
            background: #fff;
            border: 2px solid #81c408;
            border-radius: 14px;
            overflow: hidden;
            height: 52px;
            transition: 0.2s ease;
        }

        .eco-search-box input{
            border: none !important;
            outline: none !important;
            flex: 1;
            padding: 12px 14px;
            font-size: 15px;
        }

        .eco-search-box button{
            width: 55px;
            height: 100%;
            border: none;
            background: #81c408;
            color: #fff;
            font-size: 16px;
            transition: 0.2s ease;
        }

        .eco-search-box button:hover{
            background: #ff9800;
        }

        /* ===== Sorting ===== */
        .eco-sort-box{
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            background: #fff;
            border: 2px solid #81c408;
            border-radius: 14px;
            padding: 10px 14px;
            height: 52px;
        }

        .eco-sort-box label{
            font-weight: 700;
            font-size: 14px;
            color: #222;
            margin: 0;
            white-space: nowrap;
        }

        .eco-sort-box select{
            border: none !important;
            outline: none !important;
            background: transparent !important;
            font-size: 14px;
            font-weight: 600;
            width: 100%;
        }

        /* ===== Categories List ===== */
        .eco-category-list{
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .eco-category-list li{
            margin-bottom: 10px;
        }

        .eco-category-link{
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid #e5e5e5;
            background: #fff;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .eco-category-link span:first-child{
            font-weight: 700;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .eco-category-link span:last-child{
            background: rgba(129,196,8,0.15);
            color: #81c408;
            font-weight: 800;
            font-size: 13px;
            padding: 4px 10px;
            border-radius: 10px;
        }

        .eco-category-link:hover{
            border-color: #ff9800;
            background: rgba(255, 152, 0, 0.08);
        }

        /* ===== ACTIVE CATEGORY FILTER ===== */
        .eco-category-link.active{
            border-color: #ff9800;
            background: rgba(129, 196, 8, 0.12);
            box-shadow: 0 6px 16px rgba(129, 196, 8, 0.18);
        }

        .eco-category-link.active span:first-child{
            color: #81c408;
        }

        .eco-category-link.active span:last-child{
            background: #81c408;
            color: #fff;
        }

        /* ===== Price Range ===== */
        .eco-price-range{
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
            font-size: 14px;
            color: #333;
            margin-top: 8px;
        }

        .eco-price-range span{
            color: #ff9800;
        }

        /* Mobile spacing */
        @media(max-width: 576px){
            .eco-filter-card{
                padding: 14px 12px;
            }
        }
    </style>

    @include('user.pages.partials.page-header', [
        'title' => __('shop'),
        'breadcrumbs' => [
            [
                'label' => __('shop'),
                'icon'  => 'fas fa-store'
            ]
        ]
    ])

    <!-- Fruits Shop Start-->
    <div class="container-fluid fruite py-2">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="row g-4 mb-5">

                        <div class="col-xl-4">
                            <div class="eco-search-box">
                                <input type="search" id="liveSearch" placeholder="{{ app()->getLocale() === 'mm' ? 'ပစ္စည်းများကို ရှာဖွေပါ...' : 'Search products...' }}" />
                                <button type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12 col-xl-4"></div>

                        <div class="col-xl-4">
                            <div class="eco-sort-box">
                                <label for="sorting">{{ app()->getLocale() === 'mm' ? 'မျိုးတူများကို စုရန် :' : 'Sorting :' }}</label>
                                <select id="liveSort">
                                    <option value="default">{{ app()->getLocale() === 'mm' ? 'မူရင်း' : 'Default' }}</option>

                                    <option value="price_low">{{ app()->getLocale() === 'mm' ? 'ဈေး (အနည်း → အများ)' : 'Price (Low → High)' }}</option>
                                    <option value="price_high">{{ app()->getLocale() === 'mm' ? 'ဈေး (အများ → အနည်း)' : 'Price (High → Low)' }}</option>

                                    <option value="newest">{{ app()->getLocale() === 'mm' ? 'အသစ်' : 'Newest' }}</option>
                                    <option value="oldest">{{ app()->getLocale() === 'mm' ? 'အဟောင်း' : 'Oldest' }}</option>

                                    <option value="name_asc">{{ app()->getLocale() === 'mm' ? 'အမည် (A → Z)' : 'Name: (A → Z)' }}</option>
                                    <option value="name_desc">{{ app()->getLocale() === 'mm' ? 'အမည် (Z → A)' : 'Name: (Z → A)' }}</option>

                                     <option value="rating_high">{{ app()->getLocale() === 'mm' ? 'အဆင့်သတ်မှတ်ချက် (အများ → အနည်း)' : 'Rating (High → Low)' }}</option>
                                    <option value="rating_low">{{ app()->getLocale() === 'mm' ? 'အဆင့်သတ်မှတ်ချက် (အနည်း → အများ)' : 'Rating (Low → High)' }}</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row g-4">
                        <div class="col-lg-3">
                            <div class="row g-4">

                                {{-- Categories --}}
                                <div class="col-lg-12">
                                    <div class="eco-filter-card">
                                        <h4 class="eco-filter-title">
                                            <i class="fas fa-layer-group"></i> Categories
                                        </h4>

                                        <ul class="eco-category-list">

                                            {{-- ALL CATEGORIES --}}
                                            <li>
                                                <a href="#"
                                                data-category="all"
                                                class="eco-category-link {{ request('category') == null || request('category') == 'all' ? 'active' : '' }}">
                                                    <span>
                                                        <i class="fas fa-globe"></i>
                                                        {{ app()->getLocale() === 'mm' ? 'အားလုံး' : 'All' }}
                                                    </span>
                                                    <span>{{ $categories->sum('products_count') }}</span>
                                                </a>
                                            </li>

                                            @foreach($categories as $category)
                                                <li>
                                                    <a href="#"
                                                    data-category="{{ $category->id }}"
                                                    data-name-en="{{ $category->name }}"
                                                    data-name-mm="{{ $category->name_mm }}"
                                                    class="eco-category-link {{ request('category') == $category->id ? 'active' : '' }}">
                                                        <span>
                                                            <i class="fas fa-leaf"></i>
                                                            {{ $category->display_name }}
                                                        </span>
                                                        <span>{{ $category->products_count ?? 0 }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                {{-- Price --}}
                                <div class="col-lg-12">
                                    <div class="eco-filter-card">
                                        <h4 class="eco-filter-title">
                                            <i class="fas fa-coins"></i> Price
                                        </h4>

                                        <!-- Currency Select -->
                                        <div class="mb-3">
                                            <select id="priceCurrency" class="form-select">
                                                <option value="">Select Currency</option>
                                                <option value="mmk">MMK</option>
                                                <option value="usd">USD</option>
                                            </select>
                                        </div>

                                        <!-- Price From -->
                                        <div class="mb-2">
                                            <input type="number"
                                                id="priceFrom"
                                                class="form-control"
                                                placeholder="From (0 minimum)"
                                                min="0">
                                        </div>

                                        <!-- Price To -->
                                        <div>
                                            <input type="number"
                                                id="priceTo"
                                                class="form-control"
                                                placeholder="To"
                                                min="0">
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-9">

                            <div id="productGrid">
                                @include('user.components.products.grid',[
                                    'products'=>$products,
                                    'showReset'=>true
                                ])
                            </div>

                            <div id="paginationArea">
                                @include('user.pages.shop.partials.pagination', ['products' => $products])
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fruits Shop End-->

<script>
document.addEventListener("DOMContentLoaded", function () {

    let selectedCategory = "{{ request('category') ?? 'all' }}";

    // ===== AUTO CATEGORY FROM HERO SEARCH =====
    const urlParams = new URLSearchParams(window.location.search);
    const heroSearch = urlParams.get("search");

    if (heroSearch && selectedCategory === "all") {

        const normalizedSearch = heroSearch.toLowerCase();

        document.querySelectorAll(".eco-category-link").forEach(link => {

            const nameEn = (link.dataset.nameEn || "").toLowerCase();
            const nameMm = (link.dataset.nameMm || "").toLowerCase();

            if (
                normalizedSearch.includes(nameEn) ||
                normalizedSearch.includes(nameMm)
            ) {
                selectedCategory = link.dataset.category;

                document.querySelectorAll(".eco-category-link")
                    .forEach(x => x.classList.remove("active"));

                link.classList.add("active");
            }
        });
    }

    const searchInput = document.getElementById("liveSearch");
    const sortSelect = document.getElementById("liveSort");
    const currencySelect = document.getElementById("priceCurrency");
    const priceFrom = document.getElementById("priceFrom");
    const priceTo = document.getElementById("priceTo");

    function fetchProducts(pageUrl = null) {

        let baseUrl = "{{ route('shop.index') }}";
        let page = null;

        if (pageUrl) {
            const urlObj = new URL(pageUrl);
            page = urlObj.searchParams.get("page");
        }

        const params = new URLSearchParams();

        if (searchInput.value.trim() !== "") {
            params.append("search", searchInput.value.trim());
        }

        if (selectedCategory !== "all") {
            params.append("category", selectedCategory);
        }

        if (sortSelect.value !== "default") {
            params.append("sort", sortSelect.value);
        }

        if (currencySelect.value !== "") {
            params.append("currency", currencySelect.value);
        }

        if (priceFrom.value !== "") {
            params.append("price_from", priceFrom.value);
        }

        if (priceTo.value !== "") {
            params.append("price_to", priceTo.value);
        }

        if (page) {
            params.append("page", page);
        }

        const finalUrl = baseUrl + "?" + params.toString();

        fetch(finalUrl, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById("productGrid").innerHTML = data.html;
            document.getElementById("paginationArea").innerHTML = data.pagination;
        })
        .catch(err => console.error(err));
    }

    // ===== LIVE SEARCH (DEBOUNCE) =====
    let searchTimer;
    searchInput.addEventListener("keyup", function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            fetchProducts();
        }, 400);
    });

    // ===== SORT CHANGE =====
    sortSelect.addEventListener("change", function () {
        fetchProducts();
    });

    // ===== PRICE RANGE =====
    [currencySelect, priceFrom, priceTo].forEach(el => {
        el.addEventListener("input", function () {
            fetchProducts();
        });
    });

    // ===== CATEGORY CLICK =====
    document.addEventListener("click", function(e){
        const cat = e.target.closest(".eco-category-link");
        if (!cat) return;

        e.preventDefault();

        selectedCategory = cat.dataset.category;

        // active class update
        document.querySelectorAll(".eco-category-link").forEach(x => x.classList.remove("active"));
        cat.classList.add("active");

        fetchProducts();
    });

    // ===== PAGINATION CLICK =====
    document.addEventListener("click", function(e){
        const pageLink = e.target.closest("#paginationArea a");
        if (!pageLink) return;

        e.preventDefault();

        if (pageLink.classList.contains("disabled")) return;

        fetchProducts(pageLink.href);
    });

    document.addEventListener("click", function(e){

        if(!e.target.closest("#resetFilters")) return;

        selectedCategory = "all";
        searchInput.value = "";
        sortSelect.value = "default";

        if(currencySelect) currencySelect.value = "";
        if(priceFrom) priceFrom.value = "";
        if(priceTo) priceTo.value = "";

        document.querySelectorAll(".eco-category-link")
            .forEach(x => x.classList.remove("active"));

        const allBtn = document.querySelector('[data-category="all"]');
        if(allBtn) allBtn.classList.add("active");

        fetchProducts();
    });

    // ===== EMPTY STATE BUTTON (UNIVERSAL) =====
    document.addEventListener("click", function(e){

        const emptyBtn = e.target.closest(".eco-empty-action-btn");
        if(!emptyBtn) return;

        const category = emptyBtn.dataset.category || "all";

        selectedCategory = category;
        searchInput.value = "";
        sortSelect.value = "default";

        if(currencySelect) currencySelect.value = "";
        if(priceFrom) priceFrom.value = "";
        if(priceTo) priceTo.value = "";

        document.querySelectorAll(".eco-category-link")
            .forEach(x => x.classList.remove("active"));

        const targetBtn = document.querySelector(`[data-category="${category}"]`);
        if(targetBtn) targetBtn.classList.add("active");

        fetchProducts();
    });

});
</script>

@endsection
