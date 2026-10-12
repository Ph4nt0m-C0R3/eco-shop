{{-- DELETE MODAL --}}
<div class="modal fade" id="deleteModal_{{ $method->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-trash"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-2">
                    Are you sure you want to delete this payment method?
                </p>

                <div class="alert alert-warning mb-0">
                    <b>{{ $method->name_en }}</b> will be permanently removed.
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    Cancel
                </button>

                <form method="POST"
                      action="{{ route('admin.settings.payment_methods.delete', $method->id) }}">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
