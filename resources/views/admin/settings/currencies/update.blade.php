{{-- UPDATE MODAL --}}
<div class="modal fade" id="updateCurrencyModal_{{ $currency->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content shadow">

            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-coins text-primary"></i>
                    Update Currency ({{ $currency->code }})
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST"
                  action="{{ route('admin.settings.currencies.update', $currency->id) }}">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label fw-bold" for="eco-field-99e6f7-1-{{ $currency->id }}">Rate</label>
                            <input type="number"
                                   step="0.01"
                                   name="rate"
                                   value="{{ $currency->rate }}"
                                   class="form-control"
                                   required id="eco-field-99e6f7-1-{{ $currency->id }}">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold" for="eco-field-99e6f7-2-{{ $currency->id }}">Status</label>
                            <select name="is_active" class="form-select" id="eco-field-99e6f7-2-{{ $currency->id }}">
                                <option value="1" {{ $currency->is_active == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0" {{ $currency->is_active == 0 ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
