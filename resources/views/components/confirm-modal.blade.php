<div id="deleteConfirmOverlay" class="eco-delete-overlay d-none">
    <div class="eco-delete-box">

        <div class="mb-3">
            <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
        </div>

        <h5 class="mb-2 text-danger fw-semibold">
            Remove Profile Photo?
        </h5>

        <p class="text-muted mb-4">
            This action cannot be undone.
        </p>

        <div class="d-flex justify-content-center gap-3">
            <button type="button"
                    class="btn btn-outline-secondary rounded-pill px-4"
                    onclick="hideDeleteConfirm()">
                Cancel
            </button>

            <button type="button"
                    class="btn btn-danger rounded-pill px-4"
                    onclick="confirmDeleteAvatar()">
                Yes, Remove
            </button>
        </div>

    </div>
</div>
