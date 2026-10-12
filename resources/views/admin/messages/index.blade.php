@extends('admin.layouts.master')

@section('main_content')

<style>
/* Professional Message UI */
.message-item {
    padding: 18px;
    border-bottom: 1px solid #eee;
    transition: 0.2s ease;
}

.message-item:hover {
    background: #f8f9fc;
}

.message-unread {
    background: #eef4ff;
    border-left: 4px solid #0d6efd;
}

.message-name {
    font-weight: 600;
    font-size: 15px;
}

.message-preview {
    color: #6c757d;
    font-size: 14px;
}

.message-meta {
    font-size: 13px;
    color: #999;
}

.message-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.newsletter,
.contact-message {
    font-weight: 700;
    color: #1b5e20;
}
</style>

<div class="container-fluid my-4">

    <!-- Header -->
    <div class="eco-header-common eco-header-sm mb-4">
        <div class="eco-header-left">
            <i class="fa-solid fa-envelope eco-header-icon"></i>
            <div>
                <h4 class="eco-header-title">Subscribers & Messages</h4>
                <p class="eco-header-subtitle">
                    Manage newsletter subscribers and contact form messages
                </p>
            </div>
        </div>

        <span class="eco-header-right badge badge-light px-3 py-2">
            Subscribers : {{ $subscribers->total() }} |
            Messages : {{ $contacts->total() }}
        </span>
    </div>

    @include('components.partials.success-alert')
    @include('components.partials.error-alert')

    <div class="card eco-card">
        <div class="card-body">

            {{-- ================= SUBSCRIBERS (FULL ROW) ================= --}}
            <h4 class="mb-3 newsletter">Newsletter Subscribers</h4>

            <div class="table-responsive mb-4">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60" class="text-center">#</th>
                            <th>Email</th>
                            <th width="180">Subscribed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscribers as $sub)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $sub->email }}</td>
                                <td>{{ $sub->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No subscribers found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="eco-pagination">
                    {{ $subscribers->links() }}
                </div>
            </div>

            <hr>

            {{-- ================= CONTACT MESSAGES (PROFESSIONAL) ================= --}}
            <h4 class="mb-3 contact-message">Contact Messages</h4>

            <div class="card border-0 shadow-sm">

                @forelse($contacts as $msg)

                <a href="{{ route('admin.messages.show', $msg->id) }}"
                class="text-decoration-none text-dark">

                    <div class="message-item d-flex align-items-start gap-3
                        {{ !$msg->is_read ? 'message-unread' : '' }}">

                        {{-- Avatar --}}
                        <div class="message-avatar">
                            {{ strtoupper(substr($msg->name, 0, 1)) }}
                        </div>

                        {{-- Content --}}
                        <div class="flex-grow-1">

                            <div class="d-flex justify-content-between align-items-center">

                                <div class="message-name">
                                    {{ $msg->name }}

                                    @if(!$msg->is_read)
                                        <span class="badge bg-primary ms-2">New</span>
                                    @endif
                                </div>

                                <div class="message-meta">
                                    {{ $msg->created_at->diffForHumans() }}
                                </div>

                            </div>

                            <div class="message-preview mt-1">
                                {{ \Illuminate\Support\Str::limit($msg->message, 80) }}
                            </div>

                            <div class="message-meta mt-1">
                                {{ $msg->email }}
                            </div>

                        </div>

                    </div>

                </a>

                @empty
                    <div class="p-4 text-center text-muted">
                        No messages found
                    </div>
                @endforelse

            </div>

            <div class="eco-pagination mt-3">
                {{ $contacts->links() }}
            </div>

        </div>
    </div>
</div>

@endsection
