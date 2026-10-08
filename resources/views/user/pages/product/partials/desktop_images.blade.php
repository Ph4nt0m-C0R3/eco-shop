<!-- Main Product Image -->
<div class="luxury-image-wrapper">
    <div class="luxury-image-frame">
        @if($product->eco_badge)
        <div class="luxury-badge luxury-eco-badge">
            <i class="fas fa-leaf"></i>
            <span>{{ $product->display_eco_badge }}</span>
        </div>
        @endif

        <img id="mainProductImage"
            src="{{ $product->primaryImage
                ? asset('storage/'.$product->primaryImage->image)
                : asset('default/no-image.png') }}"
            class="luxury-main-image"
            alt="{{ $product->display_name }}">
    </div>
</div>

<!-- Gallery Thumbnails -->
@if($product->images->count() > 1)
<div class="luxury-thumbnail-gallery mt-4">
    <div class="luxury-thumbnail-list">
        @foreach($product->images as $img)
        <div class="luxury-thumbnail-item">
            <img src="{{ asset('storage/'.$img->image) }}"
                class="luxury-thumbnail"
                data-full="{{ asset('storage/'.$img->image) }}"
                alt="{{ $product->display_name }}">
        </div>
        @endforeach
    </div>
</div>
@endif
