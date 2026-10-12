<?php

namespace App\Services;

use App\Models\User;
use App\Support\AccountSecurity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Validation\ValidationException;

class AdminMfa
{
    public static function verified(Request $request, User $user, int $seconds = 7200): bool
    {
        $proof = $request->session()->get('admin_mfa', []);
        return $user->isAdmin() && $user->two_factor_confirmed_at && $user->two_factor_secret
            && isset($proof['identity'], $proof['at']) && is_string($proof['identity'])
            && hash_equals(AccountSecurity::fingerprint($user), $proof['identity'])
            && $proof['at'] > now()->timestamp - $seconds;
    }

    public static function markVerified(Request $request, User $user): void
    {
        $request->session()->regenerate(true);
        $request->session()->put('admin_mfa', ['identity' => AccountSecurity::fingerprint($user), 'at' => now()->timestamp]);
    }

    private function withinAttemptLimit(User $user): bool
    {
        if (!$user->two_factor_attempts_at || $user->two_factor_attempts_at->lte(now()->subMinutes(5))) {
            $user->forceFill(['two_factor_attempts' => 0, 'two_factor_attempts_at' => now()])->save();
        }
        return $user->two_factor_attempts < 5;
    }

    private function matchingStep(#[\SensitiveParameter] string $secret, #[\SensitiveParameter] string $code, ?int $previous = null): int|false
    {
        if (!preg_match('/^[0-9]{6}$/D', $code)) return false;
        return (new Google2FA)->verifyKeyNewer($secret, $code, $previous ?? 0, 1, intdiv(now()->timestamp, 30));
    }

    public function challenge(Request $request, #[\SensitiveParameter] string $code, #[\SensitiveParameter] string $recovery): User
    {
        $user = DB::transaction(function () use ($request, $code, $recovery) {
            $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            if (!$user->isAdmin() || !$user->two_factor_confirmed_at || !$this->withinAttemptLimit($user)) return null;
            $valid = false;
            if ($code !== '' && $recovery === '') {
                $step = $this->matchingStep($user->two_factor_secret, $code, $user->two_factor_last_step);
                if ($step !== false) {
                    $user->two_factor_last_step = $step;
                    $valid = true;
                }
            } elseif ($recovery !== '' && $code === '') {
                $hash = hash('sha256', strtolower(str_replace('-', '', trim($recovery))));
                $hashes = $user->two_factor_recovery_codes ?? [];
                foreach ($hashes as $index => $stored) {
                    if (hash_equals($stored, $hash)) {
                        unset($hashes[$index]);
                        $user->two_factor_recovery_codes = array_values($hashes);
                        $valid = true;
                        break;
                    }
                }
            }
            $user->two_factor_attempts += $valid ? 0 : 1;
            $user->save();
            return $valid ? $user : null;
        }, 3);
        if (!$user) throw ValidationException::withMessages(['code' => __('auth-security.mfa_invalid')]);
        self::markVerified($request, $user);
        return $user;
    }

    public function enroll(Request $request, #[\SensitiveParameter] string $password, #[\SensitiveParameter] string $code): array
    {
        $setup = $request->session()->get('mfa_setup', []);
        $result = DB::transaction(function () use ($request, $password, $code, $setup) {
            $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            if (!$this->withinAttemptLimit($user)) return null;
            $validSetup = isset($setup['identity'], $setup['secret'], $setup['expires'])
                && hash_equals(AccountSecurity::fingerprint($user), $setup['identity']) && $setup['expires'] > now()->timestamp;
            if (!$user->isAdmin() || !$validSetup || $user->two_factor_confirmed_at && !self::verified($request, $user, 300)) return null;
            $step = $this->matchingStep($setup['secret'], $code);
            if ($step === false || !$user->password || !Hash::check($password, $user->password)) {
                $user->increment('two_factor_attempts');
                return null;
            }
            $codes = $this->recoveryCodes();
            $user->forceFill([
                'two_factor_secret' => $setup['secret'], 'two_factor_confirmed_at' => now(),
                'two_factor_last_step' => $step, 'two_factor_recovery_codes' => $this->hashCodes($codes),
                'two_factor_attempts' => 0,
            ])->save();
            return [$user, $codes];
        }, 3);
        if (!$result) throw ValidationException::withMessages(['code' => __('auth-security.mfa_invalid')]);
        AccountSecurity::keepCurrentSession($request, $result[0]);
        self::markVerified($request, $result[0]);
        return $result[1];
    }

    public function regenerateRecovery(Request $request, #[\SensitiveParameter] string $password, #[\SensitiveParameter] string $code): array
    {
        $result = DB::transaction(function () use ($request, $password, $code) {
            $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            if (!self::verified($request, $user) || !$this->withinAttemptLimit($user)) return null;
            $step = $this->matchingStep($user->two_factor_secret, $code, $user->two_factor_last_step);
            if ($step === false || !$user->password || !Hash::check($password, $user->password)) {
                $user->increment('two_factor_attempts');
                return null;
            }
            $codes = $this->recoveryCodes();
            $user->forceFill(['two_factor_recovery_codes' => $this->hashCodes($codes),
                'two_factor_last_step' => $step, 'auth_version' => $user->auth_version + 1])->save();
            return [$user, $codes];
        }, 3);
        if (!$result) throw ValidationException::withMessages(['code' => __('auth-security.mfa_invalid')]);
        AccountSecurity::keepCurrentSession($request, $result[0]);
        self::markVerified($request, $result[0]);
        return $result[1];
    }

    private function recoveryCodes(): array
    {
        return array_map(fn () => implode('-', str_split(bin2hex(random_bytes(16)), 8)), range(1, 10));
    }
    private function hashCodes(array $codes): array
    {
        return array_map(fn ($code) => hash('sha256', str_replace('-', '', $code)), $codes);
    }
}
