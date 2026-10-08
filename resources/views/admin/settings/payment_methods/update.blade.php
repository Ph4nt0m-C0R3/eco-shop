<style>
    /* ===== ECO MODAL RESPONSIVE FIX ===== */
    .eco-modal-responsive {
        width: 95%;
        max-width: 900px;
        margin: auto;
        border-radius: 18px;
        display: flex;
        flex-direction: column;
    }

    /* Scrollable body */
    .eco-modal-body {
        max-height: 70vh;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Hide scrollbar but keep scrolling */
    .eco-modal-body::-webkit-scrollbar {
        width: 0px;
        background: transparent;
    }

    .eco-modal-body {
        scrollbar-width: none; /* Firefox */
    }

    /* Tablet */
    @media (max-width: 991.98px){
        .eco-modal-responsive {
            max-width: 700px;
        }
    }

    /* Mobile */
    @media (max-width: 767.98px){

        .eco-modal-responsive {
            width: 100%;
            max-width: 100%;
            height: 100vh;
            border-radius: 0;
        }

        .eco-modal-body {
            max-height: calc(100vh - 120px);
            padding: 15px;
        }

        /* stack everything nicely */
        .eco-modal-body .row > div {
            margin-bottom: 12px;
        }
    }

    .eco-modal-footer {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    /* Mobile buttons full width */
    @media (max-width: 576px){
        .eco-modal-footer {
            flex-direction: column;
        }

        .eco-modal-footer .btn {
            width: 100%;
        }
    }

    @media (max-width: 576px){
        .eco-modal-header h5 {
            font-size: 14px;
            line-height: 1.4;
        }
    }
</style>

<!-- ===== Eco Update Payment Method Modal ===== -->
<div class="eco-modal-overlay" id="updateModal_{{ $method->id }}">
    <div class="eco-modal eco-modal-responsive">

        <!-- Header -->
        <div class="eco-modal-header">
            <h5>
                <i class="fas fa-pen text-success mr-2"></i>
                Update Payment Method (#{{ $method->id }})
            </h5>
            <button class="eco-modal-close"
                    onclick="closeEcoModalById('updateModal_{{ $method->id }}')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="eco-modal-body">
            <form method="POST"
                  action="{{ route('admin.settings.payment_methods.update', $method->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="small font-weight-bold text-muted">Name (English)</label>
                        <input type="text"
                               name="name_en"
                               value="{{ $method->name_en }}"
                               class="form-control rounded-pill"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="small font-weight-bold text-muted">Name (Myanmar)</label>
                        <input type="text"
                               name="name_mm"
                               value="{{ $method->name_mm }}"
                               class="form-control rounded-pill">
                    </div>

                    <div class="col-md-6">
                        <label class="small font-weight-bold text-muted">Type</label>
                        <select name="type" class="form-control rounded-pill" required>
                            <option value="manual" {{ $method->type == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="cod" {{ $method->type == 'cod' ? 'selected' : '' }}>Cash On Delivery</option>
                            <option value="bank" {{ $method->type == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="e_wallet" {{ $method->type == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="small font-weight-bold text-muted">Currency</label>
                        <select name="currency_code" class="form-control rounded-pill" required>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->code }}"
                                    {{ $method->currency_code == $currency->code ? 'selected' : '' }}>
                                    {{ $currency->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="small font-weight-bold text-muted">Account Name</label>
                        <input type="text"
                               name="account_name"
                               value="{{ $method->account_name }}"
                               class="form-control rounded-pill">
                    </div>

                    <div class="col-md-6">
                        <label class="small font-weight-bold text-muted">Account Number</label>
                        <input type="text"
                               name="account_number"
                               value="{{ $method->account_number }}"
                               class="form-control rounded-pill">
                    </div>

                    <!-- ICON -->
                    <div class="col-md-6">
                        <img id="icon_preview_{{ $method->id }}"
                            src="{{ $method->icon ? asset('storage/'.$method->icon) : asset('default/no-image.png') }}" class="mb-2"
                            style="width:80px;height:80px;border-radius:12px;border:1px solid #ddd;">

                        <label class="small font-weight-bold text-muted">Payment Icon</label>

                        <input type="file"
                            name="icon"
                            class="form-control rounded-pill"
                            style="flex:1; min-width:140px;"
                            onchange="previewUpdateIcon(event, {{ $method->id }})">
                    </div>

                    <!-- QR -->
                    <div class="col-md-6">
                        <img id="qr_preview_{{ $method->id }}"
                             src="{{ $method->qr_image ? asset('storage/'.$method->qr_image) : asset('default/no-image.png') }}"
                             class="mb-2"
                             style="width:80px;height:80px;border-radius:12px;border:1px solid #ddd;">

                        <label class="small font-weight-bold text-muted">QR Image</label>
                        <input type="file"
                               name="qr_image"
                               class="form-control rounded-pill"
                               onchange="previewUpdateQR(event, {{ $method->id }})">
                    </div>

                    <div class="col-md-6">
                        <label class="small font-weight-bold text-muted">Status</label>
                        <select name="is_active" class="form-control rounded-pill">
                            <option value="1" {{ $method->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$method->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                </div>

                <!-- Footer -->
                <div class="eco-modal-footer mt-4">
                    <button type="button"
                            class="btn btn-light rounded-pill px-4"
                            onclick="closeEcoModalById('updateModal_{{ $method->id }}')">
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
