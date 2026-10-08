@extends('admin.layouts.master')

@section('main_content')

<div class="container-fluid my-4">

    {{-- PAGE HEADER (Eco Style — No Back Button) --}}
    <div class="eco-header-common eco-header-sm mb-4">

        <div class="eco-header-left">

            {{-- Icon --}}
            <i class="fas fa-box-open eco-header-icon"></i>

            {{-- Title + Subtitle --}}
            <div>
                <h4 class="eco-header-title">Create Product</h4>
                <p class="eco-header-subtitle">
                    Add a new product to EcoShop catalog
                </p>
            </div>

        </div>

        <span class="eco-header-right badge eco-create-badge px-3 py-2">
            <i class="fas fa-plus-circle me-1"></i>
            Create Mode
        </span>

    </div>

    @include('components.partials.success-alert')
    @include('components.partials.error-alert')
    @include('components.partials.warning-alert')

    <form action="{{ route('product#create') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">

            {{-- LEFT --}}
            <div class="col-lg-8">

                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        {{-- Names --}}
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Name (EN)</label>
                                <input type="text"
                                    name="name_en"
                                    value="{{ old('name_en') }}"
                                    class="form-control @error('name_en') is-invalid @enderror">

                                @error('name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="form-label">Name (MM)</label>
                                <input type="text" name="name_mm"
                                    value="{{ old('name_mm') }}"
                                    class="form-control @error('name_mm') is-invalid @enderror">

                                @error('name_mm')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Descriptions --}}
                        <div class="mb-3">
                            <label>Description (EN)</label>
                            <textarea name="description_en"
                                    class="form-control @error('description_en') is-invalid @enderror"
                                    rows="3">{{ old('description_en') }}</textarea>

                            @error('description_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Description (MM)</label>
                            <textarea name="description_mm" class="form-control @error('description_mm') is-invalid @enderror" rows="3">{{ old('description_mm') }}</textarea>

                            @error('description_mm')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Images --}}
                        <div id="imagePreview" class="d-flex flex-wrap gap-2 mt-3"></div>

                        <div class="mb-3">
                            <label>Product Images</label>
                            <input type="file"
                                name="images[]"
                                data-image-preview="product"
                                data-show-primary="true"
                                class="form-control @error('images') is-invalid @enderror"
                                multiple>

                            @if ($errors->has('images') || $errors->has('images.*'))
                                <div class="text-danger small mt-1">
                                    {{ $errors->first('images') ?? $errors->first('images.*') }}
                                </div>
                            @endif

                            <small class="text-muted">First image will be primary</small>
                        </div>
                        <small class="text-muted d-block mt-1">
                            Max 5 images · JPG, PNG, WEBP · 2MB each
                        </small>

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
                            <select name="category_id"
                                    class="form-control @error('category_id') is-invalid @enderror">
                                <option value="">-- Select --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Currency Selector --}}
                        <div class="mb-3">
                            <label>Currency</label>
                            <select name="currency"
                                class="form-control @error('currency') is-invalid @enderror">
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="MMK" {{ old('currency') == 'MMK' ? 'selected' : '' }}>MMK</option>
                            </select>
                        </div>

                        {{-- Single Price Field --}}
                        <div class="mb-3">
                            <label>Price</label>
                            <input type="number"
                                name="price"
                                step="0.01"
                                min="0.01"
                                value="{{ old('price') }}"
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
                                min="1"
                                value="{{ old('stock') }}"
                                class="form-control @error('stock') is-invalid @enderror">

                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Eco --}}
                        <div class="mb-3">
                            <label>Eco Badge (EN)</label>
                            <input type="text"
                                name="eco_badge"
                                value="{{ old('eco_badge') }}"
                                class="form-control"
                                placeholder="Recyclable / Organic">
                        </div>

                        <div class="mb-3">
                            <label>Eco Badge (MM)</label>
                            <input type="text"
                                name="eco_badge_mm"
                                value="{{ old('eco_badge_mm') }}"
                                class="form-control"
                                placeholder="ပြန်လည်အသုံးပြုနိုင်သော">
                        </div>

                        <button class="btn btn-primary w-100 mt-3">
                            Create Product
                        </button>

                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
