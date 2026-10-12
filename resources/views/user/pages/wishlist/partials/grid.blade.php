@include('user.components.products.grid', [
    'products'     => $products,
    'emptyTitle'   => $emptyTitle ?? null,
    'emptyMessage' => $emptyMessage ?? null,
    'emptyButton'  => $emptyButton ?? null,
    'buttonText'   => $buttonText ?? null,
])
