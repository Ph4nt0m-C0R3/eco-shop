<style>
    /* ===== LIGHT THEME PRODUCT MODAL - PROFESSIONAL DESIGN ===== */
    .modal-body-custom::-webkit-scrollbar {
        display: none;
    }

    .product-modal {
        border-radius: 16px;
        overflow: hidden;
        border: none;
        max-width: 900px;
        width: 90vw;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
    }

    /* Header - Professional */
    .modal-header-custom {
        padding: 1rem 1.5rem;
        border: none;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        position: relative;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.2rem;
        line-height: 1.4;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .product-code {
        font-size: 0.75rem;
        opacity: 0.9;
        font-weight: 400;
        background: rgba(255,255,255,0.15);
        padding: 2px 10px;
        border-radius: 10px;
        font-family: 'SF Mono', 'Roboto Mono', monospace;
    }

    .modal-close-btn {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 0;
        font-size: 0.9rem;
        backdrop-filter: blur(10px);
    }

    .modal-close-btn:hover {
        background: rgba(255,255,255,0.2);
        transform: rotate(90deg);
    }

    /* Main Layout */
    .modal-body-custom {
        padding: 0;
        background: #ffffff;
        max-height: 85vh;
        overflow-y: auto;
    }

    .product-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }

    @media (max-width: 992px) {
        .product-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ===== PROFESSIONAL IMAGE GALLERY REDESIGN ===== */
    .image-section {
        padding: 2rem;
        background: #fafbfc;
        display: flex;
        flex-direction: column;
        height: 100%;
        border-right: 1px solid #f0f2f5;
    }

    .main-image-container {
        height: 320px;
        background: #ffffff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        border: 1px solid #eef1f6;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    }

    .main-image-wrapper {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .main-image {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.08));
    }

    .main-image:hover {
        transform: scale(1.02);
    }

    .image-zoom-btn {
        position: absolute;
        bottom: 16px;
        right: 16px;
        background: white;
        border: 1px solid #eef1f6;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: all 0.3s ease;
        z-index: 2;
        font-size: 0.85rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    a.image-zoom-btn {
        text-decoration: none;
    }

    .image-zoom-btn:hover {
        background: #4f46e5;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 16px rgba(79, 70, 229, 0.2);
    }

    /* Professional Thumbnails Grid */
    .thumbnails-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, 80px);
        gap: 12px;
        margin-top: 1.5rem;
        padding: 0.5rem;
        background: #fafbfc;
        border-radius: 10px;
        border: 1px solid #f0f2f5;
        justify-content: center;
    }

    .thumbnail-item {
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        background: white;
    }

    .thumbnail-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .thumbnail-item:hover {
        border-color: #e0e7ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .thumbnail-item:hover img {
        transform: scale(1.05);
    }

    .thumbnail-item.active {
        border-color: #4f46e5;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
    }

    .thumbnail-item.active::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border: 2px solid #4f46e5;
        border-radius: 6px;
        pointer-events: none;
    }

    /* Details Section - Professional */
    .details-section {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        height: 100%;
        background: #ffffff;
    }

    /* Price Card - Professional */
    .price-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 1.2rem;
        margin-bottom: 1.2rem;
        border: 1px solid #eef1f6;
        position: relative;
        overflow: hidden;
    }

    .price-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.03) 0%, rgba(124, 58, 237, 0.03) 100%);
    }

    .price-main {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 4px;
        position: relative;
        z-index: 1;
    }

    .current-price {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .price-currency {
        font-size: 1.2rem;
        color: #64748b;
        font-weight: bold;
        margin-left: 2px;
    }

    .price-sub {
        color: #64748b;
        font-size: 1rem;
        font-weight: 400;
        position: relative;
        z-index: 1;
    }

    .price-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        z-index: 1;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
    }

    /* Stats Grid - Professional */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.2rem;
    }

    .stat-card {
        background: #f8fafc;
        border-radius: 10px;
        padding: 0.85rem;
        border: 1px solid #eef1f6;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        background: white;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background: linear-gradient(to bottom, #4f46e5, #7c3aed);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-label {
        font-size: 0.65rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .stat-value {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    /* Full-width stat (Category) */
    .stat-full {
        grid-column: 1 / -1;
    }

    /* Description - Professional */
    .description-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.2rem;
        margin-bottom: 1.5rem;
        border: 1px solid #eef1f6;
        flex: 1;
        min-height: 160px;
        max-height: 180px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .description-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #eef1f6;
    }

    .description-title {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .description-content {
        color: #475569;
        line-height: 1.6;
        font-size: 0.85rem;
        overflow-y: auto;
        flex: 1;
        padding-right: 8px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .description-content::-webkit-scrollbar {
        width: 4px;
    }

    .description-content::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 2px;
    }

    .description-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 2px;
    }

    /* Actions - Professional */
    .action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-top: auto;
    }

    .action-btn {
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 500;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 9px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
        border: none;
        cursor: pointer;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .action-btn.edit {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
    }

    .action-btn.edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
    }

    .action-btn.close {
        background: white;
        color: #64748b;
        border: 1px solid #eef1f6;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    }

    .action-btn.close:hover {
        background: #f8fafc;
        border-color: #e0e7ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .product-modal {
            width: 95vw;
            margin: 10px;
        }

        .modal-header-custom {
            padding: 0.875rem 1rem;
        }

        .modal-title {
            font-size: 1.1rem;
        }

        .image-section, .details-section {
            padding: 1.25rem;
        }

        .main-image-container {
            height: 240px;
            padding: 1rem;
        }

        .main-image {
            max-height: 180px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .action-buttons {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .thumbnails-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            padding: 0.5rem;
        }

        .current-price {
            font-size: 1.75rem;
        }

        .price-card {
            padding: 1.25rem;
        }

        .carousel-control-prev,
        .carousel-control-next {
            opacity: 1;
            background: rgba(255, 255, 255, 0.9);
        }
    }

    @media (max-width: 576px) {
        .thumbnails-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .description-section {
            max-height: 180px;
        }

        .stat-card {
            padding: 0.875rem;
        }
    }

    /* Animation - Professional */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .product-grid > * {
        animation: fadeInUp 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    /* Image Loading Animation */
    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }
        100% {
            background-position: 200px 0;
        }
    }

    .main-image.loading,
    .thumbnail-item img.loading {
        background: linear-gradient(90deg, #f0f2f5 25%, #e6e9ef 50%, #f0f2f5 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }
</style>

<div class="modal fade" id="productViewModal-{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content product-modal">

            {{-- Header --}}
            <div class="modal-header-custom">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <h5 class="modal-title">
                            {{ $product->name_en }}
                            <span class="product-code">#{{ $product->code ?? 'PROD' . $product->id }}</span>
                        </h5>
                    </div>
                    <button type="button" class="btn modal-close-btn" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            {{-- Main Content Grid --}}
            <div class="modal-body-custom">
                <div class="product-grid">

                    {{-- Image Gallery --}}
                    <div class="image-section">
                        <div class="main-image-container">
                            @if($product->images->first())
                            <a href="{{ asset('storage/' . $product->images->first()->image) }}"
                               class="image-zoom-btn"
                               data-fancybox="product-{{ $product->id }}"
                               title="Zoom">
                                <i class="fas fa-search-plus"></i>
                            </a>
                            @endif

                            <div class="carousel slide" id="carouselProduct-{{ $product->id }}" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @forelse($product->images as $key => $image)
                                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image->image) }}"
                                             class="main-image"
                                             alt="{{ $product->name_en }}"
                                             data-bs-target="#carouselProduct-{{ $product->id }}"
                                             data-bs-slide-to="{{ $key }}">
                                    </div>
                                    @empty
                                    <div class="carousel-item active">
                                        <img src="{{ asset('default/product-placeholder.png') }}"
                                             class="main-image"
                                             alt="No Image">
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Thumbnails Grid --}}
                        @if($product->images->count() > 1)
                        <div class="thumbnails-grid">
                            @foreach($product->images as $index => $image)
                            <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}"
                                 data-bs-target="#carouselProduct-{{ $product->id }}"
                                 data-bs-slide-to="{{ $index }}">
                                <img src="{{ asset('storage/' . $image->image) }}" alt="Thumbnail {{ $index + 1 }}">
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Product Details --}}
                    <div class="details-section">

                        {{-- Price + Availability --}}
                        <div class="price-card">
                            <div class="price-main">
                                <div class="current-price">{{ number_format($product->price_mmk) }}</div>
                                <div class="price-currency">Ks.</div>
                            </div>
                            <div class="price-sub">$ {{ $product->price_usd }}</div>

                            @if($product->available_stock > 0)
                                <div class="price-badge">In Stock</div>
                            @else
                                <div class="price-badge bg-danger">Out of Stock</div>
                            @endif
                        </div>

                        {{-- Quick Facts --}}
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-label">Stock</div>
                                <div class="stat-value {{ $product->available_stock > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $product->available_stock }} units
                                </div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-label">Eco Badge</div>
                                <div class="stat-value">
                                    {{ $product->eco_badge ?? 'Eco Friendly' }}
                                </div>
                            </div>

                            <div class="stat-card stat-full">
                                <div class="stat-label">Category</div>
                                <div class="stat-value">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="description-section">
                            <div class="description-header">
                                <div class="description-title">
                                    <i class="fas fa-align-left"></i>
                                    Product Description
                                </div>
                                @if($product->updated_at)
                                    <small class="text-muted">
                                        Updated {{ $product->updated_at->format('M d, Y') }}
                                    </small>
                                @endif
                            </div>

                            <div class="description-content">
                                {{ $product->description_en ?: 'No description available for this product.' }}
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="action-buttons">
                            <a href="{{ route('product#edit.page', $product->id) }}" class="action-btn edit">
                                <i class="fas fa-edit"></i>
                                Edit Product
                            </a>

                            <button type="button" class="action-btn close" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i>
                                Close
                            </button>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Thumbnail click handler
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.thumbnail-item');
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });
</script>
