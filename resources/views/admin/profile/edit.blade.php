<div class="col-lg-8 mb-4">
    <div class="card eco-card">
        <div class="card-body">

            <h5 class="eco-section-title mb-4">
                <i class="fas fa-user-edit mr-2"></i>
                Edit Profile
            </h5>

            <form method="POST" action="{{ route('admin#profile.update') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Full Name</label>
                        <input type="text"
                               name="name"
                               class="form-control eco-input rounded-pill"
                               value="{{ old('name', auth()->user()->name) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email"
                               name="email"
                               class="form-control eco-input rounded-pill"
                               value="{{ old('email', auth()->user()->email) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Phone</label>
                        <input type="text"
                               name="phone"
                               class="form-control eco-input rounded-pill"
                               value="{{ old('phone', auth()->user()->phone) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Address</label>
                        <input type="text"
                               name="address"
                               class="form-control eco-input rounded-pill"
                               value="{{ old('address', auth()->user()->address) }}">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin#profile.show') }}"
                       class="btn btn-light rounded-pill px-4">
                        Cancel
                    </a>

                    <button class="btn btn-success rounded-pill px-4">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
