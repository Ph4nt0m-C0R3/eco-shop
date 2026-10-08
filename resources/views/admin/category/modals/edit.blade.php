<!-- ===== Eco Edit Category Modal ===== -->
<div class="eco-modal-overlay" id="ecoEditModal">
    <div class="eco-modal">

        <!-- Header -->
        <div class="eco-modal-header">
            <h5>
                <i class="fas fa-leaf text-success mr-2"></i>
                Edit Category
            </h5>
            <button class="eco-modal-close" onclick="closeEcoModalById('ecoEditModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="eco-modal-body">
            <form id="editCategoryForm" method="post">
                @csrf
                @method('PUT') <!-- Laravel method spoofing -->

                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Category Name</label>
                    <input
                        type="text"
                        name="name"
                        id="editCategoryName"
                        value="{{ old('name') }}"
                        class="form-control rounded-pill @error('name', 'edit') is-invalid @enderror"
                    >

                    @error('name', 'edit')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Category Name (Myanmar)</label>
                    <input
                        type="text"
                        name="name_mm"
                        id="editCategoryNameMm"
                        value="{{ old('name_mm') }}"
                        class="form-control rounded-pill @error('name_mm', 'edit') is-invalid @enderror"
                    >
                    @error('name_mm', 'edit')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Description</label>
                    <textarea
                        name="description"
                        id="editCategoryDescription"
                        class="form-control rounded-lg"
                    >{{ old('description') }}</textarea>
                </div>

                <!-- Footer -->
                <div class="eco-modal-footer justify-content-center">
                    <button
                    type="button"
                    class="btn btn-light rounded-pill px-4"
                    onclick="closeEcoModalById('ecoEditModal')">
                    Cancel
                </button>

                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-check mr-1"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
