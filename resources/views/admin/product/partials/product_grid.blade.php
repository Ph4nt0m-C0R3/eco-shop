{{-- Product Grid --}}
@if ($products->count())

    <div class="row row-cols-2 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-3">

        @foreach ($products as $product)
            <div class="col product-item"
                data-name="{{ strtolower($product->name_en) }}"
                data-price="{{ $product->price_mmk }}"
                data-category="{{ strtolower(optional($product->category)->name) }}">
                <div class="product-card">

                    <div class="product-img-container">

                        {{-- Eco badge --}}
                        @if($product->eco_badge)
                            <span class="eco-badge">
                                <i class="fa-solid fa-leaf"></i> {{ $product->eco_badge }}
                            </span>
                        @endif

                        {{-- Stock badge --}}
                        @if($product->available_stock > 10)
                            <span class="stock-badge in-stock">In Stock</span>
                        @elseif($product->available_stock > 0)
                            <span class="stock-badge low-stock">Low Stock</span>
                        @else
                            <span class="stock-badge" style="color:#dc2626;background:rgba(220,38,38,.1)">
                                Out of Stock
                            </span>
                        @endif

                        {{-- Image count badge --}}
                        @php
                            $imageCount = $product->images?->count() ?? 0;
                        @endphp

                        @if($imageCount > 1)
                            <span class="image-count-badge">
                                <i class="fa-solid fa-images"></i>
                                {{ $imageCount }}
                            </span>
                        @endif

                        <img
                            src="{{ $product->primaryImage ? asset('storage/' . $product->primaryImage->image) : asset('default/product-placeholder.png') }}"
                            class="product-img"
                            alt="{{ $product->name_en }}">
                    </div>

                    <div class="product-body">
                        <div class="product-title">{{ $product->name_en }}</div>

                        @if($product->category)
                            <div class="product-category">
                                {{ $product->category->name }}
                            </div>
                        @endif

                        <div class="product-price">
                            {{ number_format($product->price_mmk) }} Ks.
                        </div>

                        <div class="product-usd">
                            $ {{ number_format($product->price_usd, 2) }}
                        </div>

                        <div class="product-stock">
                            Stock : {{ $product->stock }}
                        </div>
                    </div>

                    <div class="card-actions">
                        <div class="action-grid">
                            <button class="action-btn view-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#productViewModal-{{ $product->id }}">
                                View
                            </button>

                            <a href="{{ route('product#edit.page', $product->id) }}"
                            class="action-btn edit-btn text-center">
                            Edit
                            </a>

                            <button
                                type="button"
                                class="action-btn delete-btn w-100 js-delete"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name_en }}"
                                data-url="{{ route('product#delete', $product->id) }}">
                                Delete
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        @endforeach

    </div>

@else

    {{-- Empty State --}}
    <div class="col-12">
        <div class="text-center py-5 my-5">
            <div class="mb-4">
                <i class="fa-solid fa-box-open fa-3x text-muted"></i>
            </div>
            <h4 class="text-muted mb-3">No Products Found</h4>
            <p class="text-muted mb-4">Start adding products to your EcoShop collection</p>
            <a href="{{ route('product#create.page') }}" class="btn btn-success px-4">
                <i class="fa-solid fa-plus me-2"></i>
                Add First Product
            </a>
        </div>
    </div>

@endif
