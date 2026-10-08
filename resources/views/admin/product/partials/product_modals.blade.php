@foreach ($products as $product)
    @include('admin.product.partials.product_modal', ['product' => $product])
@endforeach
