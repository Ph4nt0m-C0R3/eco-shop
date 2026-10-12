@extends('admin.layouts.master')

@section('main_content')

<style>

/* ===== PAGE WRAPPER ===== */
.admin-view-page {
    min-height: calc(100vh - 80px);
    background: linear-gradient(180deg, #f6faf7, #eef6f0);
    padding: 2.5rem;
}

/* ===== PAGE HEADER ===== */
.admin-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.admin-page-header h2 {
    font-weight: 700;
    color: #fff;
}

.admin-page-header p {
    margin: 0;
    color: #fff;
}

/* ===== CONTENT GRID ===== */
.admin-view-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 2rem;
}

@media (max-width: 992px) {
    .admin-view-grid {
        grid-template-columns: 1fr;
    }
}

/* ===== MESSAGE PANEL ===== */
.admin-message-panel {
    background: #fff;
    border-radius: 1.25rem;
    padding: 2rem;
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}

.admin-message-panel h4 {
    font-weight: 700;
    color: #1b5e20;
    margin-bottom: 1.5rem;
}

/* ===== INFO PANEL ===== */
.admin-info-panel {
    background: #fff;
    border-radius: 1.25rem;
    padding: 2rem;
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}

.info-label {
    font-size: 13px;
    color: #6c757d;
}

.info-value {
    font-weight: 600;
    margin-bottom: 1rem;
}

/* ===== MESSAGE CONTENT ===== */
.message-content-box {
    background: #f8f9fc;
    border-radius: 12px;
    padding: 1.5rem;
    line-height: 1.7;
    white-space: pre-wrap;
    border: 1px solid #eee;
}

/* ===== AVATAR ===== */
.message-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 1rem;
}

</style>

<div class="admin-view-page">

    <!-- PAGE HEADER -->
    <div class="admin-page-header">

        <div>
            <h2>
                <i class="fa-solid fa-envelope mr-2"></i>
                Message Details
            </h2>
            <p>View full contact message information</p>
        </div>

        <a href="{{ route('admin.messages') }}"
           class="btn btn-light btn-eco text-white">
            <i class="fas fa-arrow-left"></i> Back to Messages
        </a>

    </div>

    <!-- PAGE CONTENT -->
    <div class="admin-view-grid">

        <!-- LEFT MESSAGE PANEL -->
        <div class="admin-message-panel">

            <h4>Message</h4>

            <div class="message-content-box">{{ $contact->message }}</div>

        </div>

        <!-- RIGHT INFO PANEL -->
        <div class="admin-info-panel">

            <div class="text-center">
                <div class="message-avatar mx-auto">
                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                </div>
                <h5 class="mb-0">{{ $contact->name }}</h5>
                <small class="text-muted">{{ $contact->email }}</small>
            </div>

            <hr>

            <div>
                <div class="info-label">Sender Name</div>
                <div class="info-value">{{ $contact->name }}</div>

                <div class="info-label">Email Address</div>
                <div class="info-value">{{ $contact->email }}</div>

                <div class="info-label">Sent At</div>
                <div class="info-value">
                    {{ $contact->created_at->format('d M Y, h:i A') }}
                </div>

                <div class="info-label">Status</div>
                <div class="info-value">
                    <span class="badge bg-success">Read</span>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
