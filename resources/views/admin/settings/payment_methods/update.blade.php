{{-- UPDATE MODAL --}}
<div class="modal fade" id="updateModal_{{ $method->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow">

            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-pen text-primary"></i>
                    Update Payment Method (#{{ $method->id }})
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST"
                  action="{{ route('admin.settings.payment_methods.update', $method->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="eco-field-8169fa-1-{{ $method->id }}">Name (English)</label>
                            <input type="text"
                                   name="name_en"
                                   value="{{ $method->name_en }}"
                                   class="form-control"
                                   required id="eco-field-8169fa-1-{{ $method->id }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="eco-field-8169fa-2-{{ $method->id }}">Name (Myanmar)</label>
                            <input type="text"
                                   name="name_mm"
                                   value="{{ $method->name_mm }}"
                                   class="form-control" id="eco-field-8169fa-2-{{ $method->id }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="eco-field-8169fa-3-{{ $method->id }}">Type</label>
                            <select name="type" class="form-select" required id="eco-field-8169fa-3-{{ $method->id }}">
                                <option value="manual" {{ $method->type == 'manual' ? 'selected' : '' }}>Manual</option>
                                <option value="cod" {{ $method->type == 'cod' ? 'selected' : '' }}>Cash On Delivery</option>
                                <option value="bank" {{ $method->type == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="e_wallet" {{ $method->type == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="eco-field-8169fa-4-{{ $method->id }}">Currency</label>
                            <select name="currency_code" class="form-select" required id="eco-field-8169fa-4-{{ $method->id }}">
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->code }}"
                                        {{ $method->currency_code == $currency->code ? 'selected' : '' }}>
                                        {{ $currency->code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="eco-field-8169fa-5-{{ $method->id }}">Account Name</label>
                            <input type="text"
                                   name="account_name"
                                   value="{{ $method->account_name }}"
                                   class="form-control" id="eco-field-8169fa-5-{{ $method->id }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="eco-field-8169fa-6-{{ $method->id }}">Account Number</label>
                            <input type="text"
                                   name="account_number"
                                   value="{{ $method->account_number }}"
                                   class="form-control" id="eco-field-8169fa-6-{{ $method->id }}">
                        </div>

                        {{-- ICON --}}
                        <div class="col-md-6">
                            <div class="mt-2">
                                <img id="icon_preview_{{ $method->id }}"
                                     src="{{ $method->icon ? asset('storage/'.$method->icon) : asset('storage/default/no-image.png') }}"
                                     style="width:80px; height:80px; object-fit:cover; border-radius:12px; border:1px solid #ddd;">
                            </div>

                            <label class="form-label fw-bold" for="eco-field-8169fa-7-{{ $method->id }}">Payment Icon (512x512)</label>

                            <input type="file"
                                   name="icon"
                                   class="form-control"
                                   accept="image/png,image/webp,image/jpeg"
                                   onchange="previewUpdateIcon(event, {{ $method->id }})" id="eco-field-8169fa-7-{{ $method->id }}">
                        </div>

                        {{-- QR --}}
                        <div class="col-md-6">
                            <div class="mt-2">
                                <img id="qr_preview_{{ $method->id }}"
                                     src="{{ $method->qr_image ? asset('storage/'.$method->qr_image) : asset('storage/default/no-image.png') }}"
                                     style="width:80px; height:80px; object-fit:cover; border-radius:12px; border:1px solid #ddd;">
                            </div>

                            <label class="form-label fw-bold" for="eco-field-8169fa-8-{{ $method->id }}">QR Image</label>
                            <input type="file"
                                   name="qr_image"
                                   class="form-control"
                                   accept="image/png,image/webp,image/jpeg"
                                   onchange="previewUpdateQR(event, {{ $method->id }})" id="eco-field-8169fa-8-{{ $method->id }}">
                        </div>

                        {{-- ACTIVE --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="eco-field-8169fa-9-{{ $method->id }}">Status</label>
                            <select name="is_active" class="form-select" id="eco-field-8169fa-9-{{ $method->id }}">
                                <option value="1" {{ $method->is_active == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0" {{ $method->is_active == 0 ? 'selected' : '' }}>
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
