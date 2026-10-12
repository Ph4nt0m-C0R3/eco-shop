<?php

namespace App\Services;

use App\Models\User;
use App\Support\AccountSecurity;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class PasswordRecovery
{
    private function credentials(Request $request, bool $admin, bool $reset = false): array
    {
        return array_merge($request->only($reset ? ['email', 'token', 'password', 'password_confirmation'] : ['email']), [
            'provider' => 'local', 'role' => $admin ? fn ($query) => $query->whereIn('role', ['admin', 'superadmin']) : 'user',
        ]);
    }
    public function send(Request $request, bool $admin): void
    {
        try {
            Password::broker($admin ? 'admins' : 'users')->sendResetLink($this->credentials($request, $admin));
        } catch (\Throwable $exception) { report($exception); }
    }
    public function reset(Request $request, bool $admin): bool
    {
        return DB::transaction(function () use ($request, $admin) {
            // Serialize validation and consumption across both password brokers.
            $user = User::where('email', $request->input('email'))->lockForUpdate()->first();
            if (!$user || $user->provider !== 'local' || ($admin ? !$user->isAdmin() : $user->role !== 'user')) return false;
            return Password::broker($admin ? 'admins' : 'users')->reset($this->credentials($request, $admin, true), function (User $user, $password) {
                $fresh = AccountSecurity::changePassword($user, $password);
                event(new PasswordReset($fresh));
            }) === Password::PASSWORD_RESET;
        }, 3);
    }
}
