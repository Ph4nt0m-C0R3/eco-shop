@extends('user.layouts.master')

@section('content')

<style>
:root {
    --theme-primary: #81c408;
    --theme-secondary: #ff9800;
    --theme-primary-light: #e8f5e9;
    --theme-secondary-light: #fff3e0;
}

/* Custom button styles */
.btn-theme-primary {
    background-color: var(--theme-primary);
    border-color: var(--theme-primary);
    color: white;
}
.btn-theme-primary:hover {
    background-color: #6aab00;
    border-color: #6aab00;
    color: white;
}
.btn-theme-secondary {
    background-color: var(--theme-secondary);
    border-color: var(--theme-secondary);
    color: white;
}
.btn-theme-secondary:hover {
    background-color: #e68900;
    border-color: #e68900;
    color: white;
}
.btn-outline-theme-secondary {
    background-color: transparent;
    border: 1px solid var(--theme-secondary);
    color: var(--theme-secondary);
}
.btn-outline-theme-secondary:hover {
    background-color: var(--theme-secondary);
    color: white;
}

/* Quantity pill */
.qty-pill{
    display: inline-flex;
    align-items: center;
    border: 1px solid #d1d5db;
    border-radius: 999px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 1px 2px rgba(0,0,0,0.02);
}
.qty-btn{
    border: none;
    background: #f3f4f6;
    width: 32px;
    height: 32px;
    font-size: 18px;
    font-weight bold;
    cursor: pointer;
    transition: all 0.2s;
    color: #374151;
}
.qty-btn:hover{
    background: var(--theme-primary);
    color: white;
}
.qty-value{
    width: 40px;
    border: none;
    text-align: center;
    font-weight: 600;
    background: transparent;
    color: #1f2937;
}
.qty-value:focus{
    outline: none;
    background: #f9fafb;
}

/* Eco badge - now with light green background */
.text-success {
    color: #166534 !important;
    background: var(--theme-primary-light);
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
    margin-top: 4px;
}

/* Remove button */
.remove-cart {
    background-color: var(--theme-secondary);
    border: none;
    color: white;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s, transform 0.1s;
}
.remove-cart:hover {
    background-color: #e68900;
    transform: scale(1.05);
}

/* Summary card - enhanced */
.card.shadow-sm {
    border-top: 3px solid var(--theme-primary);
    background-color: #fefefe;
    border-radius: 20px !important;
}

/* Focus outline for inputs */
input:focus {
    outline: none;
}

/* ===== ENHANCED TABLE STYLES ===== */
/* Table header - more prominent */
.table thead th {
    background-color: var(--theme-primary) !important;
    color: white !important;
    font-weight: 600;
    font-size: 0.9rem;
    letter-spacing: 0.3px;
    text-transform: uppercase;
    border-bottom: none;
    padding: 1rem 0.8rem;
    vertical-align: middle;
}
/* Override the table-light class if present */
.table thead.table-light th {
    background-color: var(--theme-primary) !important;
    color: white !important;
}

/* Table rows - alternating backgrounds for readability */
.table tbody tr {
    background-color: #ffffff;
    transition: background-color 0.2s;
}
.table tbody tr:nth-child(even) {
    background-color: #f8faf5;  /* very light green */
}
.table tbody tr:hover {
    background-color: var(--theme-secondary-light);  /* soft orange */
}

/* Table cells - improved typography */
.table tbody td {
    padding: 1.2rem 0.8rem;
    vertical-align: middle;
    border-bottom: 1px solid #e5e7eb;
    color: #1f2937;
}

/* Product image - slight border */
.table tbody td:first-child img {
    border: 2px solid #e5e7eb;
    transition: border 0.2s;
}
.table tbody tr:hover td:first-child img {
    border-color: var(--theme-secondary);
}

/* Product name */
.table tbody td:nth-child(2) strong {
    font-size: 1.1rem;
    font-weight: 700;
    color: #111827;
}

/* Price - make MMK stand out */
.table tbody td:nth-child(3) {
    font-weight: 1000;
    font-size: 1rem;
    color: #374151;
}
.table tbody td:nth-child(3) small {
    font-size: 1rem;
    color: #6b7280;
}

/* Total column - theme primary for emphasis */
.table tbody td:nth-child(5) strong {
    color: var(--theme-primary);
    font-size: 1.2rem;
    font-weight: 700;
}
.table tbody td:nth-child(5) small {
    font-size: 1rem;
    font-weight: 800;
    color: #6b7280;
}

/* Remove button cell - align center */
.table tbody td:last-child {
    text-align: center;
}

/* Summary card adjustments */
.card.shadow-sm.p-4 {
    background: #ffffff;
    border: none;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02) !important;
}
.card.shadow-sm.p-4 h4 {
    color: #1f2937;
    font-weight: 700;
}
.card.shadow-sm.p-4 hr {
    border: none;
    height: 2px;
    background: linear-gradient(90deg, var(--theme-primary-light), transparent);
}
.card.shadow-sm.p-4 .d-flex {
    font-size: 1.1rem;
    padding: 6px 0;
}
.card.shadow-sm.p-4 .d-flex strong {
    color: var(--theme-primary);
    font-size: 1.25rem;
}

@media (max-width: 991px) {
    .cart-desktop {
        display: none;
    }
}
@media (min-width: 992px) {
    .cart-mobile {
        display: none;
    }
}
</style>

@include('user.pages.partials.page-header', [
    'title' => __('cart.title'),
    'breadcrumbs' => [
        [
            'label' => __('shop'),
            'url'   => route('shop.index'),
            'icon'  => 'fas fa-store'
        ],
        [
            'label' => __('cart.title'),
            'icon'  => 'fas fa-shopping-cart'
        ]
    ]
])

<div class="container" style="margin-top:40px; margin-bottom:80px;">

    @if(count($cart) == 0)

        @include('user.components.products.empty', [
            'title' => __('cart.title'),
            'message' => __('cart.empty'),
            'button' => 'all',
            'buttonText' => __('cart.continue_shopping')
        ])

    @else

    <!-- CART TABLE -->
    <div class="cart-desktop">
        <div class="card shadow-sm p-3" style="border-radius:14px;">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:100px;" class="text-center">{{ __('cart.item') }}</th>
                            <th>{{ __('cart.name') }}</th>
                            <th style="width:120px;">{{ __('cart.price') }}</th>
                            <th style="width:150px;" class="text-center">{{ __('cart.quantity') }}</th>
                            <th style="width:150px">{{ __('cart.total') }}</th>
                            <th style="width:80px" class="text-center"></th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($cart as $item)
                        <tr data-id="{{ $item['id'] }}">
                            <!-- IMAGE -->
                            <td data-label="{{ __('cart.item') }}">
                                <img src="{{ $item['image'] }}" style="width:70px; height:70px; border-radius:10px; object-fit:cover;">
                            </td>

                            <!-- NAME + ECO BADGE -->
                            <td data-label="{{ __('cart.name') }}">
                                <strong>{{ $item['name'] }}</strong><br>
                                @if(!empty($item['eco_badge']))
                                    <small class="text-success">{{ $item['eco_badge'] }}</small>
                                @endif
                            </td>

                            <!-- PRICE -->
                            <td data-label="{{ __('cart.price') }}">
                                {{ number_format($item['price_mmk']) }} Ks.<br>
                                <small class="text-muted">$ {{ number_format($item['price_usd'], 2) }}</small>
                            </td>

                            <!-- QUANTITY -->
                            <td data-label="{{ __('cart.quantity') }}" class="text-center">
                                <div class="qty-pill">
                                    <button class="qty-btn qty-minus" data-id="{{ $item['id'] }}">−</button>
                                    <input type="text" class="qty-value qty-input" value="{{ $item['quantity'] }}"
                                        inputmode="numeric" pattern="[0-9]*" data-id="{{ $item['id'] }}"
                                        data-stock="{{ $item['stock'] }}"
                                        data-price-mmk="{{ $item['price_mmk'] }}" data-price-usd="{{ $item['price_usd'] }}">
                                    <button class="qty-btn qty-plus" data-id="{{ $item['id'] }}">+</button>
                                </div>
                            </td>

                            <!-- TOTAL -->
                            <td data-label="{{ __('cart.total') }}">
                                <strong class="row-total-mmk" data-id="{{ $item['id'] }}">
                                    {{ number_format($item['price_mmk'] * $item['quantity']) }} Ks.
                                </strong><br>
                                <small class="text-muted row-total-usd" data-id="{{ $item['id'] }}">
                                    $ {{ number_format($item['price_usd'] * $item['quantity'], 2) }}
                                </small>
                            </td>

                            <!-- REMOVE -->
                            <td data-label="{{ __('cart.action') }}" class="text-center">
                                <button class="remove-cart" data-id="{{ $item['id'] }}">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="cart-mobile">
        @include('user.pages.cart.mobile')
    </div>

    <!-- SUMMARY (RIGHT SIDE BELOW TABLE) -->
    <div class="row mt-4">
        <div class="col-md-6"></div>

        <div class="col-md-6">
            <div class="card shadow-sm p-4" style="border-radius:14px;">
                <h4 style="font-weight:700;">{{ __('cart.summary') }}</h4>
                <hr>

                <p class="d-flex justify-content-between">
                    <span>{{ __('cart.subtotal_mmk') }}</span>
                    <strong id="subtotal-mmk">{{ number_format($subtotal_mmk) }} Ks.</strong>
                </p>

                <p class="d-flex justify-content-between">
                    <span>{{ __('cart.subtotal_usd') }}</span>
                    <strong id="subtotal-usd">$ {{ number_format($subtotal_usd, 2) }}</strong>
                </p>

                <a href="{{ route('user.checkout') }}"
                class="btn btn-theme-primary w-100 mt-3">
                    {{ __('cart.proceed_checkout') }}
                </a>

                <button class="btn btn-outline-theme-secondary w-100 mt-2" id="clearCart">
                    {{ __('cart.clear_cart') }}
                </button>
            </div>
        </div>
    </div>

    @endif
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
function syncQuantity(product_id, quantity){

    /* ===== DESKTOP ===== */
    let desktopInput = $('.qty-input[data-id="'+product_id+'"]');

    if(desktopInput.length){
        desktopInput.val(quantity);
    }

    /* ===== MOBILE ===== */
    let mobileItem = $('.mobile-cart-item[data-id="'+product_id+'"]');

    if(mobileItem.length){
        mobileItem.find('.qty-number').text(quantity);
    }

    /* ===== RECALCULATE TOTALS ===== */
    recalculateAllTotals();
}

$(document).ready(function(){

    function showEmptyCart(){

        $('.cart-desktop').remove();
        $('.cart-mobile').remove();
        $('.row.mt-4').remove();
        location.reload();
    }

    function recalculateAllTotals(){

        let subtotalMMK = 0;
        let subtotalUSD = 0;

        $('.qty-input').each(function(){

            let qty = parseInt($(this).val());
            let priceMMK = parseFloat($(this).data('price-mmk'));
            let priceUSD = parseFloat($(this).data('price-usd'));
            let id = $(this).data('id');

            let rowMMK = qty * priceMMK;
            let rowUSD = qty * priceUSD;

            $('.row-total-mmk[data-id="'+id+'"]').text(rowMMK.toLocaleString() + ' Ks.');
            $('.row-total-usd[data-id="'+id+'"]').text('$ ' + rowUSD.toFixed(2));

            subtotalMMK += rowMMK;
            subtotalUSD += rowUSD;
        });

        /* Desktop summary */
        $('#subtotal-mmk').text(subtotalMMK.toLocaleString() + ' Ks.');
        $('#subtotal-usd').text('$ ' + subtotalUSD.toFixed(2));

        /* Mobile footer */
        $('#mobile-total-usd').text('$ ' + subtotalUSD.toFixed(2));
    }

    function updateCart(product_id, quantity){

        $.ajax({
            url: "{{ route('cart.update') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: product_id,
                quantity: quantity
            },
            success: function(res){

                if(res.cart_count !== undefined){
                    updateCartBadge(res.cart_count);
                }

                if(res.success && res.message){
                    if(typeof showToast === "function"){
                        showToast(res.message, "success");
                    }
                }
            },
            error: function(xhr){

                let msg = "{{ __('cart.something_wrong') }}";

                if(xhr.status === 401){
                    msg = "{{ __('cart.login_required') }}";
                }

                if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message){
                    msg = xhr.responseJSON.message;
                }

                if(typeof showToast === "function"){
                    showToast(msg, "error");
                }else{
                    alert(msg);
                }
            }
        });
    }

    // PLUS
    $(document).on('click', '.qty-plus', function(){

        let input = $(this).siblings('.qty-input');
        let id = input.data('id');
        let stock = parseInt(input.data('stock'));
        let current = parseInt(input.val());

        if(current >= stock){
            if(typeof showToast === "function"){
                showToast("Only " + stock + " items available in stock.", "warning");
            }
            return;
        }

        let qty = current + 1;

        input.val(qty);
        recalculateAllTotals();
        updateCart(id, qty);
    });

    // MINUS
    $(document).on('click', '.qty-minus', function(){

        let input = $(this).siblings('.qty-input');
        let id = input.data('id');
        let current = parseInt(input.val());

        if(current <= 1) return;

        let qty = current - 1;

        input.val(qty);              // instant UI update
        recalculateAllTotals();      // instant price update

        updateCart(id, qty);         // background sync
    });

    // MANUAL INPUT
    $(document).on('input blur', '.qty-input', function(){
        let product_id = $(this).data('id');

        let clean = $(this).val().replace(/\D/g,'');
        let quantity = parseInt(clean);

        let stock = parseInt($(this).data('stock'));

        if(isNaN(quantity) || quantity < 1){
            quantity = 1;
        }

        if(quantity > stock){
            quantity = stock;

            if(typeof showToast === "function"){
                showToast("Only " + stock + " items available in stock.", "warning");
            }
        }

        $(this).val(quantity);

        recalculateAllTotals();      // immediate
        updateCart(product_id, quantity);
    });

    /* ================= MOBILE QUANTITY ================= */
    function updateMobileTotals(){

        let subtotalMMK = 0;
        let subtotalUSD = 0;

        $('.mobile-cart-item').each(function(){

            let qty = parseInt($(this).find('.qty-number').text());
            let priceMMK = parseFloat($(this).data('price-mmk'));
            let priceUSD = parseFloat($(this).data('price-usd'));

            subtotalMMK += qty * priceMMK;
            subtotalUSD += qty * priceUSD;
        });

        $('#mobile-total-usd').text('$ ' + subtotalUSD.toFixed(2));
    }

    // PLUS
    $(document).on('click', '.mobile-plus', function(){

        let item = $(this).closest('.mobile-cart-item');
        let id = item.data('id');

        let qtyElement = item.find('.qty-number');
        let stock = parseInt(item.data('stock'));
        let current = parseInt(qtyElement.text());

        if(current >= stock){
            if(typeof showToast === "function"){
                showToast("Only " + stock + " items available in stock.", "warning");
            }
            return;
        }

        let qty = current + 1;

        // Update mobile UI instantly
        qtyElement.text(qty);

        // Sync desktop input
        $('.qty-input[data-id="'+id+'"]').val(qty);

        // Recalculate totals instantly
        recalculateAllTotals();

        // Sync server in background
        updateCart(id, qty);
    });

    // MINUS
    $(document).on('click', '.mobile-minus', function(){

        let item = $(this).closest('.mobile-cart-item');
        let id = item.data('id');

        let qtyElement = item.find('.qty-number');
        let current = parseInt(qtyElement.text());

        if(current <= 1) return;

        let qty = current - 1;

        // Update mobile instantly
        qtyElement.text(qty);

        // Sync desktop input
        $('.qty-input[data-id="'+id+'"]').val(qty);

        // Recalculate instantly
        recalculateAllTotals();

        // Sync server
        updateCart(id, qty);
    });

    // REMOVE
    $(document).on('click', '.remove-cart', function(){

        let product_id = $(this).data('id');

        $.ajax({
            url: "{{ route('cart.remove') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: product_id
            },
            success: function(res){

                if(res.cart_count !== undefined){
                    updateCartBadge(res.cart_count);
                }

                if(res.success && res.message){
                    if(typeof showToast === "function"){
                        showToast(res.message, "success");
                    }
                }

                $('tr[data-id="'+product_id+'"]').remove();
                $('.mobile-cart-item[data-id="'+product_id+'"]').remove();

                if(res.cart_count === 0){
                    showEmptyCart();
                    return;
                }

                recalculateAllTotals();
            },
            error: function(xhr){

                let msg = "{{ __('cart.something_wrong') }}";

                if(xhr.status === 401){
                    msg = "{{ __('cart.login_required') }}";
                }

                if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message){
                    msg = xhr.responseJSON.message;
                }

                if(typeof showToast === "function"){
                    showToast(msg, "error");
                }else{
                    alert(msg);
                }
            }
        });
    });

    // CLEAR CART
    $('#clearCart').on('click', function(){
        $.ajax({
            url: "{{ route('cart.clear') }}",
            type: "POST",
            data: {_token: "{{ csrf_token() }}"},
            success: function(res){

                updateCartBadge(0);

                if(res.success && res.message){
                    if(typeof showToast === "function"){
                        showToast(res.message, "success");
                    }
                }

                showEmptyCart();
            },
            error: function(xhr){

                let msg = "{{ __('cart.something_wrong') }}";

                if(xhr.status === 401){
                    msg = "{{ __('cart.login_required') }}";
                }

                if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message){
                    msg = xhr.responseJSON.message;
                }

                if(typeof showToast === "function"){
                    showToast(msg, "error");
                }else{
                    alert(msg);
                }
            }
        });
    });

    // EMPTY COMPONENT BUTTON → REDIRECT TO SHOP
    $(document).on('click', 'button[data-category]', function(){

        let isCartEmptyPage = {{ count($cart) == 0 ? 'true' : 'false' }};

        if(!isCartEmptyPage) return;

        window.location.href = "{{ route('shop.index') }}";
    });

});
</script>

@endsection
