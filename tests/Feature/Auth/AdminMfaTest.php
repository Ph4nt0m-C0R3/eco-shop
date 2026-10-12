<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Support\AccountSecurity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class AdminMfaTest extends TestCase
{
    use RefreshDatabase;

    private function code(string $secret): string
    {
        return (new Google2FA)->oathTotp($secret, intdiv(now()->timestamp, 30));
    }
    private function login(User $user): void
    {
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password', 'remember' => true])
            ->assertRedirect(route($user->fresh()->two_factor_confirmed_at ? 'admin.mfa.challenge' : 'admin.mfa.setup'));
    }
    private function enroll(User $user): array
    {
        $this->login($user);
        $this->get(route('admin.mfa.setup'))->assertOk()->assertHeader('Cache-Control', 'no-store, private');
        $secret = session('mfa_setup.secret');
        $this->post(route('admin.mfa.enroll'), ['password' => 'password', 'code' => $this->code($secret)])
            ->assertSessionHasNoErrors()->assertRedirect(route('admin.mfa.security'));
        return [$secret, session('recovery_codes')];
    }

    public function test_password_login_requires_enrollment_and_blocks_direct_admin_requests(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);
        $this->login($user);
        $this->get(route('adminDashboard'))->assertRedirect(route('admin.mfa.setup'));
        $this->postJson('/admin/profile/update', ['name' => 'forged'])->assertForbidden();
        $this->assertSame($user->name, $user->fresh()->name);
        $this->assertEmpty(collect($this->get(route('admin.mfa.setup'))->headers->getCookies())->filter(fn ($cookie) => str_starts_with($cookie->getName(), 'remember_')));
    }
    public function test_enrollment_encrypts_secret_hides_security_fields_and_displays_codes_once(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        [$secret, $codes] = $this->enroll($user);
        $this->assertCount(10, $codes);
        $this->assertNotSame($secret, DB::table('users')->where('id', $user->id)->value('two_factor_secret'));
        $fresh = $user->fresh();
        $this->assertSame($secret, $fresh->two_factor_secret);
        $this->assertNotContains($codes[0], $fresh->two_factor_recovery_codes);
        $this->assertArrayNotHasKey('two_factor_secret', $fresh->toArray());
        $this->assertArrayNotHasKey('two_factor_recovery_codes', $fresh->toArray());
        $this->get(route('admin.mfa.security'))->assertOk()->assertSee($codes[0]);
        $this->get(route('admin.mfa.security'))->assertOk()->assertDontSee($codes[0]);
        $this->get(route('adminDashboard'))->assertOk();
    }
    public function test_enrollment_requires_password_and_a_live_matching_code(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->login($user);
        $this->get(route('admin.mfa.setup'));
        $secret = session('mfa_setup.secret');
        $this->post(route('admin.mfa.enroll'), ['password' => 'wrong', 'code' => $this->code($secret)])->assertSessionHasErrors('code');
        $this->assertNull($user->fresh()->two_factor_confirmed_at);
        $this->travel(301)->seconds();
        $this->post(route('admin.mfa.enroll'), ['password' => 'password', 'code' => $this->code($secret)])->assertSessionHasErrors('code');
        $this->assertNull($user->fresh()->two_factor_secret);
    }
    public function test_used_or_stale_totp_cannot_authenticate_and_new_code_can(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        [$secret] = $this->enroll($user);
        $used = $this->code($secret);
        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));
        $this->login($user);
        $this->post(route('admin.mfa.verify'), ['code' => $used])->assertSessionHasErrors('code');
        $this->get(route('adminDashboard'))->assertRedirect(route('admin.mfa.challenge'));
        $this->travel(31)->seconds();
        $this->post(route('admin.mfa.verify'), ['code' => $this->code($secret)])->assertSessionHasNoErrors()->assertRedirect(route('adminDashboard'));
        $this->get(route('adminDashboard'))->assertOk();
    }
    public function test_recovery_code_is_single_use_and_can_authorize_authenticator_replacement(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        [, $codes] = $this->enroll($user);
        $this->post(route('admin.logout'));
        $this->login($user);
        $this->post(route('admin.mfa.verify'), ['recovery_code' => $codes[0]])->assertRedirect(route('adminDashboard'));
        $this->assertCount(9, $user->fresh()->two_factor_recovery_codes);
        $this->get(route('admin.mfa.setup'))->assertOk();
        $this->post(route('admin.logout'));
        $this->login($user);
        $this->post(route('admin.mfa.verify'), ['recovery_code' => $codes[0]])->assertSessionHasErrors('code');
        $this->get(route('adminDashboard'))->assertRedirect(route('admin.mfa.challenge'));
    }
    public function test_failed_mfa_attempts_persist_across_login_and_reset_after_five_minutes(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        [, $codes] = $this->enroll($user);
        $this->post(route('admin.logout'));
        $this->login($user);
        for ($i = 0; $i < 5; $i++) $this->post(route('admin.mfa.verify'), ['code' => 'invalid'])->assertSessionHasErrors('code');
        $this->assertSame(5, $user->fresh()->two_factor_attempts);
        $this->post(route('admin.logout'));
        $this->login($user);
        $this->post(route('admin.mfa.verify'), ['recovery_code' => $codes[0]])->assertSessionHasErrors('code');
        $this->travel(301)->seconds();
        $this->post(route('admin.mfa.verify'), ['recovery_code' => $codes[0]])->assertRedirect(route('adminDashboard'));
    }
    public function test_recovery_regeneration_invalidates_old_codes_and_other_sessions(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);
        [$secret, $codes] = $this->enroll($user);
        $oldIdentity = AccountSecurity::fingerprint($user->fresh());
        $this->travel(31)->seconds();
        $this->post(route('admin.mfa.regenerate'), ['password' => 'password', 'code' => $this->code($secret)])
            ->assertSessionHasNoErrors()->assertRedirect(route('admin.mfa.security'));
        $this->assertNotSame($codes, session('recovery_codes'));
        $this->assertNotSame($oldIdentity, AccountSecurity::fingerprint($user->fresh()));
        $this->post(route('admin.logout'));
        $this->login($user);
        $this->post(route('admin.mfa.verify'), ['recovery_code' => $codes[0]])->assertSessionHasErrors('code');
    }
    public function test_mfa_proof_is_bound_to_account_and_has_an_expiry(): void
    {
        $first = User::factory()->create(['role' => 'admin']);
        $this->enroll($first);
        $proof = session('admin_mfa');
        $second = User::factory()->create(['role' => 'admin']);
        $this->actingAsMfaAdmin($second)->withSession(['admin_mfa' => $proof]);
        $this->get(route('adminDashboard'))->assertRedirect(route('admin.mfa.challenge'));
        $this->actingAsMfaAdmin($second);
        $this->travel(121)->minutes();
        $this->get(route('adminDashboard'))->assertRedirect(route('admin.mfa.challenge'));
    }
    public function test_mfa_setup_for_an_existing_authenticator_requires_recent_mfa(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->enroll($user);
        $this->travel(301)->seconds();
        $this->get(route('admin.mfa.setup'))->assertRedirect(route('admin.mfa.challenge'));
    }
    public function test_a_customer_cannot_enroll_or_verify_admin_mfa(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'admin')->get(route('admin.mfa.setup'))->assertForbidden();
        $this->post(route('admin.mfa.verify'), ['code' => '123456'])->assertSessionHasErrors('code');
    }
    public function test_https_admin_cookie_is_secure_scoped_and_distinct_from_customer_cookie(): void
    {
        config(['app.env' => 'production', 'app.debug' => false, 'app.url' => 'https://shop.example.test', 'session.secure' => true, 'session.driver' => 'database']);
        $customer = $this->get('https://shop.example.test/login')->assertOk();
        $admin = $this->get('https://shop.example.test/admin/login')->assertOk();
        $customerCookie = collect($customer->headers->getCookies())->first(fn ($cookie) => $cookie->getName() === config('session.customer_cookie'));
        $adminCookie = collect($admin->headers->getCookies())->first(fn ($cookie) => $cookie->getName() === config('session.admin_cookie'));
        $this->assertNotNull($customerCookie);
        $this->assertNotNull($adminCookie);
        $this->assertTrue($adminCookie->isSecure());
        $this->assertTrue($adminCookie->isHttpOnly());
        $this->assertSame('lax', $adminCookie->getSameSite());
        $this->assertSame('/admin', $adminCookie->getPath());
        $this->assertSame('/', $customerCookie->getPath());
    }
}
