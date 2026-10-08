<div class="eco-empty-state">

    <div class="eco-empty-box">

        <div class="eco-empty-icon">
            <i class="fas fa-leaf"></i>
        </div>

        <h3>{{ $title ?? 'No Products Found' }}</h3>

        <p>{{ $message ?? 'No products available right now.' }}</p>

        @isset($button)
            <button class="eco-empty-action-btn"
                    data-category="{{ $button }}">
                {{ $buttonText ?? 'View All Products' }}
            </button>
        @endisset

    </div>

</div>
