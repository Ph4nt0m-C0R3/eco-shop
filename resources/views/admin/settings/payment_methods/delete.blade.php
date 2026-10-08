<!-- ===== Eco Delete Modal ===== -->
<div class="eco-modal-overlay" id="deleteModal_{{ $method->id }}">
    <div class="eco-modal" style="max-width: 400px;">

        <div class="eco-modal-header">
            <h5 class="text-danger">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Confirm Delete
            </h5>
            <button class="eco-modal-close"
                    onclick="closeEcoModalById('deleteModal_{{ $method->id }}')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="eco-modal-body text-center">

            <p class="mb-3">
                Are you sure you want to delete this payment method?
            </p>

            <form method="POST"
                  action="{{ route('admin.settings.payment_methods.delete', $method->id) }}">
                @csrf
                @method('DELETE')

                <div class="eco-modal-footer justify-content-center">
                    <button type="button"
                            class="btn btn-light rounded-pill px-4"
                            onclick="closeEcoModalById('deleteModal_{{ $method->id }}')">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-danger rounded-pill px-4">
                        <i class="fas fa-trash mr-1"></i>
                        Delete
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
