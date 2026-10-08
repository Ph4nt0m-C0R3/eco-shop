<!-- ===== Eco Delete Product Modal ===== -->
<div class="eco-modal-overlay" id="ecoDeleteModal">
    <div class="eco-modal">

        <!-- Header -->
        <div class="eco-modal-header">
            <h5 class="text-danger">
                <i class="fas fa-trash-alt mr-2"></i>
                Delete Product
            </h5>
            <button class="eco-modal-close"
                    onclick="closeEcoModalById('ecoDeleteModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="eco-modal-body text-center">
            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>

            <p class="mb-2">
                Are you sure you want to delete
            </p>

            <h6 class="font-weight-bold text-dark" id="deleteProductName">
                Product Name
            </h6>

            <small class="text-muted d-block mt-2">
                This action cannot be undone!
            </small>
        </div>

        <!-- Footer -->
        <div class="eco-modal-footer justify-content-center">
            <button
                type="button"
                class="btn btn-light rounded-pill px-4"
                onclick="closeEcoModalById('ecoDeleteModal')">
                Cancel
            </button>

            <form id="deleteProductForm" method="POST">
                @csrf
                @method('DELETE')

                <input type="hidden" name="page" id="deletePage">

                <button
                    type="submit"
                    class="btn btn-danger rounded-pill px-4">
                    <i class="fas fa-trash mr-1"></i>
                    Delete
                </button>
            </form>
        </div>

    </div>
</div>

