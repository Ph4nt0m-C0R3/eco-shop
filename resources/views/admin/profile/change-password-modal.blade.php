<style>
    input[type="password"]::-ms-reveal {
        display: none;
    }
</style>

<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content eco-card border-0">

            <form method="POST" action="{{ route('admin.password.change') }}">
                @csrf

                <!-- Header -->
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <i class="fas fa-lock text-success me-2"></i>
                        Change Password
                    </h5>
                    <button
                        type="button"
                        class="eco-modal-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body">

                    <!-- Old Password -->
                    <div class="mb-3 text-start">
                        <div class="form-floating password-wrapper">
                            <input
                                type="password"
                                name="old_password"
                                id="oldPassword"
                                class="form-control @error('old_password') is-invalid @enderror"
                                placeholder="Old Password"
                            >
                            <label for="oldPassword">Old Password</label>

                            <i
                                class="fa-solid fa-eye-slash fa-fw password-toggle"
                                onclick="togglePassword('oldPassword', this)"
                            ></i>
                        </div>

                        @error('old_password')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-3 text-start">
                        <div class="form-floating password-wrapper">
                            <input
                                type="password"
                                name="password"
                                id="newPassword"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="New Password"
                            >
                            <label for="newPassword">New Password</label>

                            <i
                                class="fa-solid fa-eye-slash fa-fw password-toggle"
                                onclick="togglePassword('newPassword', this)"
                            ></i>
                        </div>

                        @error('password')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3 text-start">
                        <div class="form-floating password-wrapper">
                            <input
                                type="password"
                                name="password_confirmation"
                                id="confirmPassword"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                placeholder="Confirm Password"
                            >
                            <label for="confirmPassword">Confirm Password</label>

                            <i
                                class="fa-solid fa-eye-slash fa-fw password-toggle"
                                onclick="togglePassword('confirmPassword', this)"
                            ></i>
                        </div>

                        @error('password_confirmation')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <a href="{{ route('admin.password.otp.request') }}"
                        class="small text-success fw-semibold">
                        Forgot old password?
                    </a>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 justify-content-center gap-3">
                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button type="submit" class="btn eco-save-btn text-white px-4">
                        <i class="fas fa-check-circle me-1"></i>
                        Update Password
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
