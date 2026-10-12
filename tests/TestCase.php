<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();
        if (in_array($_ENV['P0_03_MYSQL'] ?? getenv('P0_03_MYSQL'), [true, 'true', '1'], true)) {
            // Check before RefreshDatabase/DatabaseMigrations can run.
            $database = $app['config']->get('database.connections.mysql');
            if ($app['config']->get('app.env') !== 'testing' || $app['config']->get('database.default') !== 'mysql'
                || $database['database'] !== 'ecoshop_security_test' || $database['username'] !== 'ecoshop_security_test'
                || $database['host'] !== '127.0.0.1' || (string) $database['port'] !== '3306' || !empty($database['url']) || !empty($database['unix_socket'])) {
                throw new \RuntimeException('The MySQL security profile requires its dedicated disposable CI database.');
            }
        }
        return $app;
    }

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
