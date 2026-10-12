<!-- Logout Confirm Modal -->
<div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; overflow:hidden;">

            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    {{ app()->getLocale() === 'mm' ? 'ထွက်ရန် အတည်ပြုပါ' : 'Confirm Logout' }}
                </h5>
                <button type="button" class="eco-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body text-center py-4">
                <i class="fas fa-sign-out-alt text-danger" style="font-size:45px;"></i>

                <h6 class="mt-3 fw-bold">
                    {{ app()->getLocale() === 'mm'
                        ? 'အကောင့်မှ ထွက်ချင်တာ သေချာပါသလား။'
                        : 'Are you sure you want to logout?' }}
                </h6>

                <p class="text-muted mb-0" style="font-size:14px;">
                    {{ app()->getLocale() === 'mm'
                        ? 'သင့်အကောင့်ကို ပြန်လည်ဝင်ရောက်ရန် ထပ်မံ Login လုပ်ရပါမည်။'
                        : 'You will need to login again to access your account.' }}
                </p>
            </div>

            <div class="modal-footer d-flex justify-content-center gap-2 pb-4">
                <button type="button"
                        class="btn btn-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">
                    {{ app()->getLocale() === 'mm' ? 'မလုပ်တော့ပါ' : 'Cancel' }}
                </button>

                <button type="button"
                        class="btn btn-danger rounded-pill px-4"
                        id="confirmLogoutBtn">
                    {{ app()->getLocale() === 'mm' ? 'ထွက်မည်' : 'Yes, Logout' }}
                </button>
            </div>

        </div>
    </div>
</div>
