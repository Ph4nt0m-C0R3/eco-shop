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
        .eco-shop-filters>summary { padding:12px 16px; border:1px solid #81c408; border-radius:12px; cursor:pointer; font-weight:700; margin-bottom:16px; }
        .eco-shop-filters[open]>summary { margin-bottom:24px; }
        .eco-search-box input { min-width:0; }
        @media(min-width:992px) { .eco-shop-filters>summary { display:none; } }
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
                    <div class="row g-3 mb-3">

                        <div class="col-md-6">
                            <div class="eco-search-box">
                                <input type="search" id="liveSearch" aria-label="{{ __('stability.search') }}" placeholder="{{ app()->getLocale() === 'mm' ? 'ပစ္စည်းများကို ရှာဖွေပါ...' : 'Search products...' }}" />
                                <button type="button" id="shopSearchButton" aria-label="{{ __('stability.search') }}">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>

                        

                        <div class="col-md-6">
                            <div class="eco-sort-box">
                                <label for="liveSort">{{ app()->getLocale() === 'mm' ? 'မျိုးတူများကို စုရန် :' : 'Sorting :' }}</label>
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
                        <aside class="col-lg-3">
                            <details id="shopFilters" class="eco-shop-filters" open>
                                <summary>{{ __('stability.filters') }}</summary>
                            <div class="row g-4">

                                {{-- Categories --}}
                                <div class="col-lg-12">
                                    <div class="eco-filter-card">
                                        <h4 class="eco-filter-title">
                                            <i class="fas fa-layer-group"></i> {{ __('stability.categories') }}
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
                                            <i class="fas fa-coins"></i> {{ __('stability.price') }}
                                        </h4>

                                        <!-- Currency Select -->
                                        <div class="mb-3">
                                            <select id="priceCurrency" aria-label="{{ __('stability.currency') }}" class="form-select">
                                                <option value="">{{ __('stability.currency') }}</option>
                                                <option value="mmk">MMK</option>
                                                <option value="usd">USD</option>
                                            </select>
                                        </div>

                                        <!-- Price From -->
                                        <div class="mb-2">
                                            <input type="number"
                                                id="priceFrom"
                                                class="form-control"
                                                placeholder="{{ __('stability.from') }}" aria-label="{{ __('stability.from') }}"
                                                min="0">
                                        </div>

                                        <!-- Price To -->
                                        <div>
                                            <input type="number"
                                                id="priceTo"
                                                class="form-control"
                                                placeholder="{{ __('stability.to') }}" aria-label="{{ __('stability.to') }}"
                                                min="0">
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </details>
                        </aside>

                        <div class="col-lg-9">

                            <div class="d-flex align-items-center gap-2 mb-2"><span id="shopStatus" role="status" aria-live="polite"></span><button type="button" id="shopRetry" class="btn btn-outline-primary btn-sm" hidden>{{ __('stability.retry') }}</button></div>
                            <div id="productGrid" aria-busy="false">
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

<script>window.ECO_SHOP={url:@json(route('shop.index')),loading:@json(__('stability.loading')),error:@json(__('stability.filter_error'))};</script>
<script src="{{ asset('user/js/shop.js') }}"></script>
@endsection
