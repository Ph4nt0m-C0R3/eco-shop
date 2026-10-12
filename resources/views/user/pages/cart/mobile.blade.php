<style>
    /* ================= MOBILE APP STYLE CART ================= */

    .cart-mobile {
        background: #f7f7f7;
    }

    .mobile-cart-wrapper {
        padding: 20px 16px;
    }

    .mobile-cart-item {
        display: grid;
        grid-template-columns: 80px 1fr;
        gap: 12px;
        background: white;
        padding: 15px;
        border-radius: 18px;
        margin-bottom: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        position: relative;
    }

    /* RIGHT SIDE GRID */
    .mobile-cart-right {
        display: grid;
        grid-template-rows: auto auto;
        row-gap: 8px;
    }

    /* ROW 1 */
    .cart-row-top strong {
        font-size: 15px;
        display: block;
    }

    .cart-row-top {
        display: flex;
        flex-direction: column;
    }

    /* ROW 2 */
    .cart-row-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mobile-cart-img{
        width: 70px;
        height: 70px;
        border-radius: 16px;
        object-fit: cover;

        justify-self: center;
        align-self: center;
    }

    .mobile-cart-info {
        flex: 1;
        margin-left: 12px;
    }

    .mobile-cart-info strong {
        font-size: 15px;
    }

    .mobile-badge {
        font-size: 12px;
        color: var(--theme-primary);
        margin: 4px 0;
    }

    .mobile-price {
        font-weight: 600;
    }

    .mobile-qty {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mobile-qty button {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: #f1f5f9;
        font-size: 18px;
    }

    .mobile-qty button:hover {
        background: var(--theme-primary);
        color: white;
    }

    .qty-number {
        font-weight: 600;
        width: 20px;
        text-align: center;
    }

    .mobile-checkout-btn {
        width: 100%;
        background: black;
        color: white;
        border: none;
        padding: 16px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 16px;
    }

    .mobile-cart-item {
        position: relative;
    }

    .mobile-remove-btn {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: none;
        background: var(--theme-secondary);
        color: white;
        font-size: 14px;
    }

    .mobile-remove-btn:hover {
        background: #e68900;
    }

    .mmk-price {
        font-weight: 700;
    }

    .usd-price {
        font-size: 13px;
        color: #6b7280;
    }
</style>

<div class="mobile-cart-wrapper">

    @foreach($cart as $item)
    <div class="mobile-cart-item" data-id="{{ $item['id'] }}"
        data-stock="{{ $item['stock'] }}"
        data-price-mmk="{{ $item['price_mmk'] }}"
        data-price-usd="{{ $item['price_usd'] }}">

        <!-- REMOVE BUTTON -->
        <button class="mobile-remove-btn remove-cart" data-id="{{ $item['id'] }}">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- LEFT COLUMN (IMAGE) -->
        <img src="{{ $item['image'] }}" class="mobile-cart-img">

        <!-- RIGHT COLUMN -->
        <div class="mobile-cart-right">

            <!-- ROW 1: TITLE + BADGE -->
            <div class="cart-row-top">
                <strong class="cart-title">{{ $item['name'] }}</strong>

                @if(!empty($item['eco_badge']))
                    <div class="mobile-badge">{{ $item['eco_badge'] }}</div>
                @endif
            </div>

            <!-- ROW 2: PRICE + QTY -->
            <div class="cart-row-bottom">
                <div class="mobile-price">
                    <div class="mmk-price">
                        {{ number_format($item['price_mmk']) }} Ks.
                    </div>
                    <div class="usd-price">
                        $ {{ number_format($item['price_usd'], 2) }}
                    </div>
                </div>

                <div class="mobile-qty">
                    <button class="mobile-minus">−</button>
                    <span class="qty-number">{{ $item['quantity'] }}</span>
                    <button class="mobile-plus">+</button>
                </div>
            </div>

        </div>
    </div>
    @endforeach

</div>
