@extends('user.layouts.master')

<link rel="stylesheet" href="{{ asset('user/css/product-detail.css') }}">

<meta name="current-user-avatar" content="{{ auth()->user()->avatar_url ?? asset('user/img/avatar.jpg') }}">
<meta name="current-user-name" content="{{ auth()->user()->name ?? 'User' }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="current-user-id" content="{{ auth()->id() }}">

@section('content')

<!-- Product Detail -->
<div class="container py-5 px-3 px-md-5" id="product-detail-container"
     data-stock="{{ $product->available_stock }}"
     data-name="{{ $product->display_name }}"
     data-id="{{ $product->id }}">
    <!-- Mobile Product Image - First on Mobile -->
    <div class="row g-4 g-md-5">
        <!-- Mobile Image Column (Hidden on Desktop, First on Mobile) -->
        <div class="col-12 d-lg-none order-1">
            <div class="luxury-image-wrapper">
                <div class="luxury-image-frame">
                    @if($product->eco_badge)
                    <div class="luxury-badge luxury-eco-badge">
                        <i class="fas fa-leaf"></i>
                        <span>{{ $product->display_eco_badge }}</span>
                    </div>
                    @endif

                    <img id="mainProductImageMobile"
                        src="{{ $product->primaryImage
                            ? asset('storage/'.$product->primaryImage->image)
                            : asset('default/no-image.png') }}"
                        class="luxury-main-image"
                        alt="{{ $product->display_name }}">
                </div>
            </div>

            <!-- Gallery Thumbnails for Mobile -->
            @if($product->images->count() > 1)
            <div class="luxury-thumbnail-gallery mt-4">
                <div class="luxury-thumbnail-list">
                    @foreach($product->images as $img)
                    <div class="luxury-thumbnail-item">
                        <img src="{{ asset('storage/'.$img->image) }}"
                            class="luxury-thumbnail"
                            data-full="{{ asset('storage/'.$img->image) }}"
                            alt="{{ $product->display_name }}">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Product Info - Second on Mobile -->
        <div class="col-lg-8 order-2 order-lg-2">
            <div class="luxury-product-info">
                @include('user.pages.product.partials.product_header')
                @include('user.pages.product.partials.price_stock')
                @include('user.pages.product.partials.quantity_cart')
                @include('user.pages.product.partials.tabs')
            </div>
        </div>

        <!-- Left Column - Images & Featured Products (Desktop) -->
        <div class="col-lg-4 d-none d-lg-block order-lg-1">
            <div class="sticky-top" style="top: 20px;">
                @include('user.pages.product.partials.desktop_images')
                @include('user.pages.product.partials.desktop_featured')
            </div>
        </div>

        <!-- Mobile Featured Products - Fourth on Mobile -->
        <div class="col-12 d-lg-none order-4 mt-4">
            @include('user.pages.product.partials.mobile_featured')
        </div>
    </div>

    <!-- Related Products Section - Fifth on Mobile -->
    @if($relatedProducts->count())
    <div class="luxury-section mt-5 pt-5 order-5">
        <div class="luxury-section-header">
            <h3 class="luxury-section-title">
                <i class="fas fa-th-large me-3"></i>
                {{ app()->getLocale() === 'mm' ? 'ဆက်စပ်ထုတ်ကုန်များ' : 'Related Products' }}
            </h3>
            <a href="{{ route('shop.index', ['category' => $product->category_id]) }}" class="luxury-view-all">
                {{ app()->getLocale() === 'mm' ? 'အားလုံးကြည့်ရန်' : 'View All' }}
                <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        @include('user.components.products.grid', [
            'products' => $relatedProducts,
            'emptyText' => app()->getLocale() === 'mm'
                ? 'ဆက်စပ်ထုတ်ကုန် မရှိပါ'
                : 'No related products found.'
        ])

    </div>
    @endif
</div>

<script src="{{ asset('user/js/product-detail.js') }}"></script>

@endsection
