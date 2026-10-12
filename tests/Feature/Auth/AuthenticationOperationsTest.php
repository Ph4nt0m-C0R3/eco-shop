<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\AdminResetPasswordNotification;
use App\Notifications\CustomerResetPasswordNotification;
use App\Notifications\PasswordChallengeNotification;
use App\Support\AccountSecurity;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthenticationOperationsTest extends TestCase
{
    use DatabaseTruncation;

    protected function beforeTruncatingDatabase(): void
    {
        if (config('database.default') === 'sqlite' && config('database.connections.sqlite.database') === ':memory:') {
            \Illuminate\Foundation\Testing\RefreshDatabaseState::$migrated = false;
        }
    }

    public function test_recovery_notifications_queue_after_commit_and_encrypt_secret_payloads(): void
    {
        config(['queue.default' => 'database', 'queue.connections.database.after_commit' => true]);
        $user = User::factory()->create();
        $secret = 'isolated-test-secret-never-in-queue-plaintext';
        DB::transaction(function () use ($user, $secret) {
            $user->notify(new PasswordChallengeNotification($secret));
            $user->notify(new CustomerResetPasswordNotification($secret));
            $user->notify(new AdminResetPasswordNotification($secret));
            $this->assertDatabaseCount('jobs', 0);
        });
        $jobs = DB::table('jobs')->get();
        $this->assertCount(3, $jobs);
        foreach ($jobs as $job) {
            $this->assertStringNotContainsString($secret, $job->payload);
            $command = json_decode($job->payload, true)['data']['command'];
            $this->assertStringNotContainsString('O:', $command);
            $this->assertStringContainsString($secret, \Illuminate\Support\Facades\Crypt::decrypt($command));
        }
    }
    public function test_operator_recovery_requires_a_reason_and_revokes_staff_security_state(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);
        $this->actingAsMfaAdmin($user);
        $before = AccountSecurity::fingerprint($user);
        $this->artisan('admin:mfa-reset', ['email' => $user->email, '--force' => true])->assertFailed();
        $this->assertNotNull($user->fresh()->two_factor_secret);
        $this->artisan('admin:mfa-reset', ['email' => $user->email, '--force' => true, '--reason' => 'Independent identity verification completed'])->assertSuccessful();
        $this->assertNull($user->fresh()->two_factor_secret);
        $this->assertNotSame($before, AccountSecurity::fingerprint($user->fresh()));
        $this->get(route('adminDashboard'))->assertRedirect(route('admin.login'));
    }
    public function test_production_rejects_unencrypted_sessions_or_overlapping_cookie_names(): void
    {
        config(['app.env' => 'production', 'app.debug' => false, 'app.url' => 'https://shop.example.test', 'session.secure' => true, 'session.encrypt' => false]);
        $this->get('https://shop.example.test/up')->assertStatus(503);
        config(['session.encrypt' => true, 'session.admin_cookie' => config('session.customer_cookie')]);
        $this->get('https://shop.example.test/up')->assertStatus(503);
    }
}
