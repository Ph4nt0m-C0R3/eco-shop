<?php

namespace App\Services;

use App\Models\PasswordChallenge;
use App\Models\User;
use App\Notifications\PasswordChallengeNotification;
use App\Support\AccountSecurity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordChallenges
{
    public function send(Request $request): void
    {
        [$user, $row, $code] = DB::transaction(function () use ($request) {
            $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            abort_unless($user->hasVerifiedEmail() && $user->provider === 'local', 403);
            $row = PasswordChallenge::where('user_id', $user->id)->lockForUpdate()->first();
            if ($row && $row->sent_at->gt(now()->subMinute())) abort(429, __('auth-security.cooldown'));
            if ($row && $row->send_window_at->gt(now()->subHour()) && $row->send_count >= 5) abort(429, __('auth-security.send_limit'));
            if ($row && $row->attempts_at->gt(now()->subMinutes(10)) && $row->attempts >= 5) abort(429, __('auth-security.attempt_limit'));
            $code = (string) random_int(100000, 999999);
            $row ??= new PasswordChallenge(['user_id' => $user->id]);
            $row->fill([
                'email' => $user->email, 'account_hash' => AccountSecurity::fingerprint($user),
                'session_hash' => hash('sha256', $request->session()->getId()),
                'code_hash' => Hash::make($code), 'grant_hash' => null,
                'expires_at' => now()->addMinutes(5), 'sent_at' => now(),
                'attempts' => $row->attempts_at?->gt(now()->subMinutes(10)) ? $row->attempts : 0,
                'attempts_at' => $row->attempts_at?->gt(now()->subMinutes(10)) ? $row->attempts_at : now(),
                'send_count' => $row->send_window_at?->gt(now()->subHour()) ? $row->send_count + 1 : 1,
                'send_window_at' => $row->send_window_at?->gt(now()->subHour()) ? $row->send_window_at : now(),
                'verified_at' => null, 'consumed_at' => null,
            ])->save();
            return [$user, $row, $code];
        }, 3);
        $request->session()->forget('password_grant');
        $request->session()->put('password_challenge', $row->id);
        try {
            $user->notify(new PasswordChallengeNotification($code));
        } catch (\Throwable $exception) {
            PasswordChallenge::whereKey($row->id)->where('code_hash', $row->code_hash)
                ->update(['code_hash' => null, 'consumed_at' => now()]);
            $request->session()->forget('password_challenge');
            \Illuminate\Support\Facades\Log::warning('Password code delivery failed.', ['exception_type' => $exception::class]);
            throw ValidationException::withMessages(['otp' => __('auth-security.delivery_failed')]);
        }
    }

    public function current(Request $request, bool $verified = false): ?PasswordChallenge
    {
        $row = PasswordChallenge::find($request->session()->get('password_challenge'));
        return $this->valid($row, $request->user(), $request, $verified) ? $row : null;
    }

    private function valid(?PasswordChallenge $row, User $user, Request $request, bool $verified): bool
    {
        return $row && $user->provider === 'local' && $user->hasVerifiedEmail() && $row->user_id === $user->id && $row->email === $user->email
            && hash_equals($row->account_hash, AccountSecurity::fingerprint($user))
            && hash_equals($row->session_hash, hash('sha256', $request->session()->getId()))
            && $row->expires_at->isFuture() && !$row->consumed_at && $row->attempts < 5
            && ($verified ? $row->verified_at && is_string($request->session()->get('password_grant'))
                && hash_equals($row->grant_hash ?? '', hash('sha256', $request->session()->get('password_grant')))
                : !$row->verified_at && $row->code_hash);
    }

    public function verify(Request $request, #[\SensitiveParameter] string $code): void
    {
        $grant = DB::transaction(function () use ($request, $code) {
            $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            $row = PasswordChallenge::whereKey($request->session()->get('password_challenge'))->lockForUpdate()->first();
            if (!$this->valid($row, $user, $request, false)) return null;
            $matches = preg_match('/^[0-9]{6}$/D', $code) && Hash::check($code, $row->code_hash);
            if (!$matches) {
                $row->increment('attempts');
                return null;
            }
            $grant = bin2hex(random_bytes(32));
            $row->update(['code_hash' => null, 'verified_at' => now(), 'grant_hash' => hash('sha256', $grant)]);
            return $grant;
        }, 3);
        // Throw after commit, so failed-attempt increments cannot roll back.
        if (!$grant) throw ValidationException::withMessages(['otp' => __('auth-security.invalid_code')]);
        $request->session()->put('password_grant', $grant);
    }

    public function reset(Request $request, #[\SensitiveParameter] string $password): User
    {
        return DB::transaction(function () use ($request, $password) {
            $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            $row = PasswordChallenge::whereKey($request->session()->get('password_challenge'))->lockForUpdate()->first();
            abort_unless($this->valid($row, $user, $request, true), 403, __('auth-security.invalid_code'));
            $row->update(['consumed_at' => now(), 'grant_hash' => null]);
            return AccountSecurity::changePassword($user, $password);
        }, 3);
    }
}
