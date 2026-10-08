<!-- ===== Eco Create Category Modal ===== -->
<div class="eco-modal-overlay" id="ecoModal">
    <div class="eco-modal">

        <!-- Header -->
        <div class="eco-modal-header">
            <h5>
                <i class="fas fa-leaf text-success mr-2"></i>
                Create New Category
            </h5>
            <button class="eco-modal-close" onclick="closeEcoModalById('ecoModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="eco-modal-body">
            <form method="post" action="{{ route('category#create') }}">
                @csrf

                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Category Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ session('open_create') ? old('name') : '' }}"
                        class="form-control rounded-pill @error('name', 'create') is-invalid @enderror"
                    >
                    @error('name', 'create')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Category Name (Myanmar)</label>
                    <input
                        type="text"
                        name="name_mm"
                        value="{{ session('open_create') ? old('name_mm') : '' }}"
                        class="form-control rounded-pill @error('name_mm', 'create') is-invalid @enderror"
                    >
                    @error('name_mm', 'create')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Description</label>
                    <textarea name="description" class="form-control rounded-lg">{{ session('open_create') ? old('description') : '' }}</textarea>
                </div>

                <!-- Footer -->
                <div class="eco-modal-footer justify-content-center">
                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        onclick="closeEcoModalById('ecoModal')">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="fas fa-check mr-1"></i>
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->hasBag('create') && session('open_create'))
<script>
document.addEventListener('DOMContentLoaded', () => {
    openEcoModalById('ecoModal');
});
</script>
@endif
