<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountSecurity
{
    public static function fingerprint(User $user): string
    {
        return hash('sha256', json_encode([
            $user->id, (int) ($user->auth_version ?? 1), $user->password, $user->email, $user->role, $user->provider, $user->provider_id,
            $user->two_factor_secret, $user->two_factor_confirmed_at?->timestamp,
        ], JSON_THROW_ON_ERROR));
    }

    public static function bind(Request $request, User $user): void
    {
        $request->session()->forget(['password_challenge', 'password_grant', 'otp_email', 'otp_verified', 'admin_mfa', 'mfa_setup', 'oauth_context', 'state', 'code_verifier']);
        $request->session()->put('auth_identity', self::fingerprint($user));
    }

    public static function changePassword(User $user, #[\SensitiveParameter] string $password): User
    {
        return DB::transaction(function () use ($user, $password) {
            $locked = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            if (!hash_equals(self::fingerprint($locked), self::fingerprint($user))) {
                throw \Illuminate\Validation\ValidationException::withMessages(['password' => __('auth-security.sign_in_again')]);
            }
            $locked->forceFill(['password' => Hash::make($password)])->save();
            return $locked;
        }, 3);
    }

    public static function keepCurrentSession(Request $request, User $user): void
    {
        $request->session()->regenerate(true);
        Auth::login($user, false);
        self::bind($request, $user);
    }
}
