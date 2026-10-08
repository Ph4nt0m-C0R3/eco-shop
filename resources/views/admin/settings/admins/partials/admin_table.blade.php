@forelse($admins as $admin)
    <tr>
        <td class="text-center">
            {{ $admins->firstItem() + $loop->index }}
        </td>

        <td class="font-weight-bold text-dark">
            {{ $admin->name }}
        </td>

        <td class="text-muted">
            {{ $admin->email }}
        </td>

        <td class="text-muted">
            {{ $admin->phone ?? '—' }}
        </td>

        <td class="text-muted">
            {{ $admin->address ?? '—' }}
        </td>

        <td class="text-center text-muted">
            {{ $admin->created_at->format('d M Y') }}
            <br>
            <small class="text-muted">
                {{ $admin->created_at->diffForHumans() }}
            </small>
        </td>

        <td class="text-center">
            @if($admin->isOnline())
                <span class="badge bg-success">
                    <i class="fas fa-circle"></i> Now
                </span>
            @else
                <span class="badge bg-secondary">
                    <i class="far fa-clock"></i>
                    {{ $admin->lastOnline() }}
                </span>
            @endif
        </td>

        <td class="text-center">
            @if($admin->isOnline())
                <span class="protected-text">
                    <i class="fas fa-lock"></i> Online
                </span>
            @else
                <button
                    type="button"
                    class="btn btn-danger action-btn js-remove-admin"
                    data-id="{{ $admin->id }}"
                    data-name="{{ $admin->name }}">
                    <i class="fas fa-trash"></i>
                </button>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center text-muted py-4">
            <i class="fas fa-info-circle"></i> No admins found.
        </td>
    </tr>
@endforelse
