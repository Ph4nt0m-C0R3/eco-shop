<!-- ===== Eco Update Currency Modal ===== -->
<div class="eco-modal-overlay" id="updateCurrencyModal_{{ $currency->id }}">
    <div class="eco-modal">

        <!-- Header -->
        <div class="eco-modal-header">
            <h5>
                <i class="fas fa-coins text-success mr-2"></i>
                Update Currency ({{ $currency->code }})
            </h5>
            <button class="eco-modal-close"
                    onclick="closeEcoModalById('updateCurrencyModal_{{ $currency->id }}')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="eco-modal-body">
            <form method="POST"
                  action="{{ route('admin.settings.currencies.update', $currency->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-muted">Rate</label>
                    <input type="number"
                           step="0.01"
                           name="rate"
                           value="{{ $currency->rate }}"
                           class="form-control rounded-pill"
                           required>
                </div>

                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-muted">Status</label>
                    <select name="is_active" class="form-control rounded-pill">
                        <option value="1" {{ $currency->is_active ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="0" {{ !$currency->is_active ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

                <!-- Footer -->
                <div class="eco-modal-footer justify-content-center">
                    <button type="button"
                            class="btn btn-light rounded-pill px-4"
                            onclick="closeEcoModalById('updateCurrencyModal_{{ $currency->id }}')">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-success rounded-pill px-4">
                        <i class="fas fa-save mr-1"></i>
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
