<style>
    .eco-view-all-link{
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 700;
        font-size: 0.95rem;
        color: #81c408;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .eco-view-all-link i{
        font-size: 0.85rem;
        transition: 0.2s ease;
    }

    .eco-view-all-link:hover{
        color: #ff9800;
        text-decoration: underline;
    }

    .eco-view-all-link:hover {
        transform: translateX(4px);
    }
</style>

@if($selectedCategory !== 'all' && $showViewAll)
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('shop.index', ['category' => $selectedCategory]) }}"
           class="eco-view-all-link">
            {{ app()->getLocale() === 'mm' ? 'အားလုံးကြည့်ရန်' : 'View All' }}
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
@endif

@include('user.components.products.grid', [
    'products' => $products,
    'emptyTitle' => 'No Products Found',
    'emptyMessage' => 'No products available right now.',
    'emptyButton' => 'all',
    'buttonText' => 'View All Products'
])
