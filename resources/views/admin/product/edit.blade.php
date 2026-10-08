@extends('admin.layouts.master')

@section('main_content')

<style>
    /* ===== Back Block Button (Whole Div Clickable) ===== */
    .eco-back-block {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        padding: 10px 18px;
        border-radius: 12px;

        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        font-weight: 600;

        text-decoration: none !important;
        box-shadow: 0 6px 18px rgba(34,197,94,0.25);
        transition: all 0.2s ease;
    }

    /* remove underline in ALL states */
    .eco-back-block:hover,
    .eco-back-block:focus,
    .eco-back-block:active,
    .eco-back-block:visited {
        text-decoration: none !important;
        color: white;
    }

    .eco-back-block:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(34,197,94,0.35);
    }

    .eco-back-block i {
        font-size: 14px;
    }
</style>

<div class="container-fluid my-4">

    {{-- PAGE HEADER --}}
    <div class="eco-header-common eco-header-sm mb-4">

        <div class="eco-header-left">
            <i class="fas fa-pen-to-square eco-header-icon"></i>

            <div>
                <h4 class="eco-header-title">Edit Product</h4>
                <p class="eco-header-subtitle">
                    Update product details and images
                </p>
            </div>
        </div>

        <a href="{{ route('product#list') }}"
        class="eco-header-right eco-back-block">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Products</span>
        </a>

    </div>

    @include('components.partials.success-alert')
    @include('components.partials.error-alert')

    <form action="{{ route('product#update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">

            {{-- LEFT --}}
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        {{-- Names --}}
                        <div class="row mb-3">
                            <div class="col">
                                <label>Name (EN)</label>
                                <input type="text" name="name_en"
                                       value="{{ old('name_en', $product->name_en) }}"
                                       class="form-control @error('name_en') is-invalid @enderror">
                                @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col">
                                <label>Name (MM)</label>
                                <input type="text" name="name_mm"
                                       value="{{ old('name_mm', $product->name_mm) }}"
                                       class="form-control @error('name_mm') is-invalid @enderror">
                                @error('name_mm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Descriptions --}}
                        <div class="mb-3">
                            <label>Description (EN)</label>
                            <textarea name="description_en"
                                      class="form-control"
                                      rows="3">{{ old('description_en', $product->description_en) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label>Description (MM)</label>
                            <textarea name="description_mm"
                                      class="form-control"
                                      rows="3">{{ old('description_mm', $product->description_mm) }}</textarea>
                        </div>

                        {{-- EXISTING IMAGES --}}
                        <label class="fw-bold">Existing Images</label>
                        <div id="image-error"
                            class="alert alert-danger d-none mt-2"></div>

                        <div class="d-flex flex-wrap gap-2 mt-3 mb-3">
                            @foreach($product->images as $image)

                                <div class="position-relative">

                                    <img src="{{ asset('storage/'.$image->image) }}"
                                        class="rounded border"
                                        style="width:120px;height:120px;object-fit:cover">

                                    {{-- REMOVE IMAGE --}}
                                    <button type="button"
                                            class="image-remove-btn"
                                            onclick="removeImage({{ $image->id }})"
                                            title="Remove image">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>

                                    {{-- PRIMARY --}}
                                    <div class="form-check text-center mt-1">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="primary_image"
                                            value="{{ $image->id }}"
                                            {{ $image->is_primary ? 'checked' : '' }}>
                                        <label class="form-check-label small">
                                            Primary
                                        </label>
                                    </div>

                                </div>

                            @endforeach

                        </div>

                        <div class="mb-3">
                            <div id="imagePreview"
                                class="d-flex flex-wrap gap-2 mt-3"></div>

                            <label>Add More Images</label>

                            <input type="file"
                                name="images[]"
                                id="productImages"
                                data-image-preview="product"
                                data-show-primary="true"
                                class="form-control"
                                multiple>

                            <small class="text-muted">
                                Max 5 images total. New uploads will keep existing images.
                            </small>

                            {{-- IMAGE VALIDATION ERROR --}}
                            @if ($errors->has('images') || $errors->has('images.*'))
                                <div class="text-danger small mt-2">
                                    {{ $errors->first('images') ?? $errors->first('images.*') }}
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        {{-- Category --}}
                        <div class="mb-3">
                            <label>Category</label>
                            <select name="category_id" class="form-control">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Currency --}}
                        <div class="mb-3">
                            <label>Currency</label>
                            <select id="currencySelect" name="currency" class="form-control @error('currency') is-invalid @enderror">
                                <option value="USD"
                                    {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>
                                    USD
                                </option>
                                <option value="MMK"
                                    {{ old('currency') == 'MMK' ? 'selected' : '' }}>
                                    MMK
                                </option>
                            </select>
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Price --}}
                        <div class="mb-3">
                            <label>Price</label>
                            <input type="number"
                                step="0.01"
                                id="priceInput"
                                name="price"
                                value="{{ old('price', old('currency', 'USD') == 'MMK' ? $product->price_mmk : $product->price_usd) }}"
                                class="form-control @error('price') is-invalid @enderror">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Stock --}}
                        <div class="mb-3">
                            <label>Stock</label>
                            <input type="number"
                                name="stock"
                                value="{{ old('stock', $product->stock) }}"
                                class="form-control @error('stock') is-invalid @enderror">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Eco --}}
                        <div class="mb-3">
                            <label>Eco Badge (EN)</label>
                            <input type="text" name="eco_badge"
                                value="{{ old('eco_badge', $product->eco_badge) }}"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Eco Badge (MM)</label>
                            <input type="text" name="eco_badge_mm"
                                value="{{ old('eco_badge_mm', $product->eco_badge_mm) }}"
                                class="form-control">
                        </div>

                        <button class="btn btn-primary w-100 mb-3">
                            Update Product
                        </button>

                        <a href="{{ url()->previous() }}"
                        class="btn btn-light border w-100 text-muted">
                            Cancel
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    function removeImage(imageId) {
        fetch(`{{ url('admin/product/image') }}/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(async res => {
            const data = await res.json();

            if (!res.ok) {
                throw data;
            }

            return data;
        })
        .then(() => {
            location.reload();
        })
        .catch(error => {
            const errorBox = document.getElementById('image-error');
            errorBox.innerText = error.message || 'Unable to remove image.';
            errorBox.classList.remove('d-none');
        });
    }
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const currencySelect = document.getElementById("currencySelect");
    const priceInput = document.getElementById("priceInput");

    const priceUsd = {{ $product->price_usd }};
    const priceMmk = {{ $product->price_mmk }};

    function updatePrice() {
        if (currencySelect.value === "MMK") {
            priceInput.value = priceMmk;
        } else {
            priceInput.value = priceUsd;
        }
    }

    currencySelect.addEventListener("change", updatePrice);
});
</script>

@endsection
