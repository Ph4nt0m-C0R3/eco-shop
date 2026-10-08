<!-- Admin Logout Confirm Modal -->
<div class="modal fade" id="adminLogoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; overflow:hidden;">

            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    Confirm Logout
                </h5>
                <button type="button" class="eco-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body text-center py-4">
                <i class="fas fa-right-from-bracket text-danger" style="font-size:45px;"></i>

                <h6 class="mt-3 fw-bold">Are you sure you want to logout?</h6>
                <p class="text-muted mb-0" style="font-size:14px;">
                    You will need to login again to access admin dashboard.
                </p>
            </div>

            <div class="modal-footer d-flex justify-content-center gap-2 pb-4">
                <button type="button"
                        class="btn btn-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button"
                        class="btn btn-danger rounded-pill px-4"
                        id="confirmAdminLogout">
                    Yes, Logout
                </button>
            </div>

        </div>
    </div>
</div>
