<!-- Quantity & Cart Section - Fixed for Mobile -->
<div class="luxury-cart-section mb-5">
    <!-- Desktop Layout -->
    <div class="d-none d-md-flex align-items-center justify-content-between gap-4">
        <!-- Quantity Control -->
        <div class="d-flex align-items-center gap-4">
            <label class="luxury-form-label mb-0">{{ app()->getLocale() === 'mm' ? 'အရေအတွက် :' : 'Quantity :' }}</label>
            <div class="luxury-quantity-control">
                <button class="luxury-qty-btn" id="decrement"
                    {{ $product->available_stock <= 0 ? 'disabled' : '' }}>
                    <i class="fas fa-minus"></i>
                </button>

                <div class="luxury-qty-display">
                    <input type="number"
                        id="quantity"
                        class="luxury-qty-input"
                        value="1"
                        min="1"
                        max="{{ $product->available_stock }}">
                </div>

                <button class="luxury-qty-btn" id="increment"
                    {{ $product->available_stock <= 0 ? 'disabled' : '' }}>
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-3">
            <button class="luxury-add-cart-btn addToCartBtn"
                data-id="{{ $product->id }}"
                data-stock="{{ $product->available_stock }}"
                data-qty-input="#quantity"
                {{ $product->available_stock <= 0 ? 'disabled' : '' }}>
                <i class="fas fa-shopping-bag me-2"></i>
                {{ app()->getLocale() === 'mm' ? 'ခြင်းထဲထည့်ရန်' : 'Add to Cart' }}
            </button>

            <button class="luxury-buy-now-btn buyNowBtn"
                data-id="{{ $product->id }}"
                data-stock="{{ $product->available_stock }}"
                data-qty-input="#quantity"
                {{ $product->available_stock <= 0 ? 'disabled' : '' }}>
                <i class="fas fa-bolt me-2"></i>
                {{ app()->getLocale() === 'mm' ? 'ယူမည်' : 'Buy Now' }}
            </button>
        </div>
    </div>

    <!-- Mobile Layout -->
    <div class="d-flex d-md-none flex-column gap-3">
        <!-- Quantity Control for Mobile -->
        <div class="d-flex flex-column align-items-center gap-2">
            <label class="luxury-form-label">{{ app()->getLocale() === 'mm' ? 'အရေအတွက်' : 'Quantity' }}</label>
            <div class="luxury-quantity-control">
                <button class="luxury-qty-btn" id="decrementMobile"
                    {{ $product->available_stock <= 0 ? 'disabled' : '' }}>
                    <i class="fas fa-minus"></i>
                </button>

                <div class="luxury-qty-display">
                    <input type="number"
                        id="quantityMobile"
                        class="luxury-qty-input"
                        value="1"
                        min="1"
                        max="{{ $product->available_stock }}">
                </div>

                <button class="luxury-qty-btn" id="incrementMobile"
                    {{ $product->available_stock <= 0 ? 'disabled' : '' }}>
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <!-- Action Buttons for Mobile -->
        <div class="d-flex flex-column gap-2">
            <button class="luxury-add-cart-btn addToCartBtn"
                data-id="{{ $product->id }}"
                data-stock="{{ $product->available_stock }}"
                data-qty-input="#quantityMobile"
                {{ $product->available_stock <= 0 ? 'disabled' : '' }}>
                <i class="fas fa-shopping-bag me-2"></i>
                {{ app()->getLocale() === 'mm' ? 'ခြင်းထဲထည့်ရန်' : 'Add to Cart' }}
            </button>

            <button class="luxury-buy-now-btn buyNowBtn"
                data-id="{{ $product->id }}"
                data-stock="{{ $product->available_stock }}"
                data-qty-input="#quantityMobile"
                {{ $product->available_stock <= 0 ? 'disabled' : '' }}>
                <i class="fas fa-bolt me-2"></i>
                {{ app()->getLocale() === 'mm' ? 'ယူမည်' : 'Buy Now' }}
            </button>
        </div>
    </div>
</div>
