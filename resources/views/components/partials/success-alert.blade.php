@if(session('success'))
<div class="alert alert-success alert-dismissible fade show position-relative"
     role="alert"
     style="border-radius:10px;padding-right:60px;">

    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>

    <button type="button"
            class="btn btn-light btn-sm d-flex align-items-center justify-content-center"
            data-bs-dismiss="alert"
            aria-label="Close"
            style="
                width:32px;
                height:32px;
                border-radius:50%;
                position:absolute;
                right:12px;
                top:50%;
                transform:translateY(-50%);
            ">
        <i class="fas fa-times"></i>
    </button>

</div>
@endif
