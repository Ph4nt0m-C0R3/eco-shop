@forelse($users as $user)
    <tr>
        <td class="text-center">
            {{ $users->firstItem() + $loop->index }}
        </td>

        <td>
            <img
                src="{{ $user->avatar_url }}"
                class="rounded-circle"
                width="40"
                height="40"
                style="object-fit: cover;">
        </td>

        <td class="font-weight-bold text-dark">
            {{ $user->name }}
        </td>

        <td class="text-muted">
            {{ $user->email }}
        </td>

        <td class="text-muted">
            {{ $user->phone ?? '—' }}
        </td>

        <td class="text-muted">
            {{ $user->address ?? '—' }}
        </td>

        <td class="text-center text-muted">
            {{ $user->created_at->format('d M Y') }}
            <br>
            <small class="text-muted">
                {{ $user->created_at->diffForHumans() }}
            </small>
        </td>

        <!-- Registered Method -->
        <td class="text-center">
            @php
                $methodKey = $user->provider;
            @endphp

            <span class="method-badge method-{{ $methodKey }}">
                @if($methodKey === 'google')
                    <i class="fab fa-google"></i> Google
                @elseif($methodKey === 'github')
                    <i class="fab fa-github"></i> GitHub
                @else
                    <i class="fas fa-envelope"></i> Local
                @endif
            </span>
        </td>

        <td class="text-center">
            @if($user->isOnline())
                <span class="badge bg-success">
                    <i class="fas fa-circle"></i> Now
                </span>
            @else
                <span class="badge bg-secondary">
                    <i class="far fa-clock"></i>
                    {{ $user->lastOnline() }}
                </span>
            @endif
        </td>

        <!-- Action -->
        @auth
            @if(auth()->user()->isSuperAdmin())
                <td class="text-center">
                    @if($user->isOnline())
                        <span class="protected-text">
                            <i class="fas fa-lock"></i> Online
                        </span>
                    @else
                        <button
                            type="button"
                            class="btn btn-danger action-btn js-remove-user"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif
                </td>
            @endif
        @endauth
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center text-muted py-4">
            <i class="fas fa-info-circle"></i> No users found.
        </td>
    </tr>
@endforelse
