<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function actingAs(\Illuminate\Contracts\Auth\Authenticatable $user, $guard = null)
    {
        parent::actingAs($user, $guard ?? ($user->isAdmin() ? 'admin' : 'web'));
        $this->withSession(['auth_identity' => \App\Support\AccountSecurity::fingerprint($user)]);
        return $this;
    }

    // Commerce tests explicitly establish an already MFA-verified staff session.
    protected function actingAsMfaAdmin(\App\Models\User $user)
    {
        if (!$user->two_factor_confirmed_at) {
            $user->forceFill(['two_factor_secret' => (new \PragmaRX\Google2FA\Google2FA)->generateSecretKey(32),
                'two_factor_confirmed_at' => now(), 'two_factor_recovery_codes' => []])->save();
        }
        return $this->actingAs($user)->withSession(['admin_mfa' => [
            'identity' => \App\Support\AccountSecurity::fingerprint($user), 'at' => now()->timestamp,
        ]]);
    }
}
