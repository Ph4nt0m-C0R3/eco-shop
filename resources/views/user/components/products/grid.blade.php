<div class="eco-products-grid">

    @forelse($products as $product)

        @include('user.components.products.card',[
            'product'=>$product
        ])

    @empty

        @include('user.components.products.empty',[
            'title'=>$emptyTitle ?? null,
            'message'=>$emptyMessage ?? null,
            'button'=>$emptyButton ?? null,
            'buttonText'=>$buttonText ?? null
        ])

    @endforelse

</div>
