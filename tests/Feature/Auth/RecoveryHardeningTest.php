<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\AdminResetPasswordNotification;
use App\Notifications\CustomerResetPasswordNotification;
use App\Support\AccountSecurity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class RecoveryHardeningTest extends TestCase
{
    use RefreshDatabase;

    private function input(User $user, string $token): array
    {
        return ['email' => $user->email, 'token' => $token, 'password' => 'a fresh and long passphrase', 'password_confirmation' => 'a fresh and long passphrase'];
    }
    public function test_reset_requests_do_not_disclose_unknown_social_or_wrong_role_accounts(): void
    {
        Notification::fake();
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => 'superadmin']);
        $social = User::factory()->create(['provider' => 'google']);
        foreach ([$user, $admin, $social] as $account) {
            $this->from('/forgot-password')->post('/forgot-password', ['email' => $account->email])
                ->assertRedirect('/forgot-password')->assertSessionHasNoErrors()->assertSessionHas('status', __('auth-security.reset_requested'));
        }
        $this->from('/forgot-password')->post('/forgot-password', ['email' => 'missing@example.test'])->assertSessionHas('status', __('auth-security.reset_requested'));
        Notification::assertSentTo($user, CustomerResetPasswordNotification::class);
        Notification::assertNotSentTo($admin, CustomerResetPasswordNotification::class);
        Notification::assertNothingSentTo($social);
        $this->from('/admin/forgot-password')->post('/admin/forgot-password', ['email' => $admin->email])
            ->assertSessionHas('success', __('auth-security.reset_requested'));
        Notification::assertSentTo($admin, AdminResetPasswordNotification::class);
    }
    public function test_superadmin_reset_uses_admin_broker_and_cannot_cross_to_customer_endpoint(): void
    {
        $admin = User::factory()->create(['role' => 'superadmin']);
        $token = Password::broker('admins')->createToken($admin);
        $this->post('/reset-password', $this->input($admin, $token))->assertSessionHasErrors('email');
        $this->post('/admin/reset-password', $this->input($admin, $token))->assertRedirect(route('admin.login'))->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check($this->input($admin, $token)['password'], $admin->fresh()->password));
        $this->post('/admin/reset-password', $this->input($admin, $token))->assertSessionHasErrors('email');
    }
    public function test_customer_reset_cannot_cross_to_admin_endpoint_and_expires(): void
    {
        $user = User::factory()->create();
        $token = Password::broker('users')->createToken($user);
        $this->post('/admin/reset-password', $this->input($user, $token))->assertSessionHasErrors('email');
        $this->travel(16)->minutes();
        $this->post('/reset-password', $this->input($user, $token))->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
    public function test_token_reset_revokes_remember_tokens_sessions_and_all_other_recovery_links(): void
    {
        $user = User::factory()->create();
        $remember = $user->remember_token;
        $identity = AccountSecurity::fingerprint($user);
        $token = Password::broker('users')->createToken($user);
        $other = Password::broker('admins')->createToken($user);
        $this->post('/reset-password', $this->input($user, $token))->assertSessionHasNoErrors();
        $this->assertNotSame($remember, $user->fresh()->remember_token);
        $this->assertFalse(Password::broker('users')->tokenExists($user->fresh(), $token));
        $this->assertFalse(Password::broker('admins')->tokenExists($user->fresh(), $other));
        $this->actingAs($user)->withSession(['auth_identity' => $identity])->get('/profile')->assertRedirect(route('login'));
    }
    public function test_long_utf8_passphrases_are_not_truncated_and_short_or_oversized_passwords_fail(): void
    {
        $user = User::factory()->create();
        $passphrase = str_repeat('လုံခြုံ', 12).' sunshine';
        $this->assertGreaterThan(72, strlen($passphrase));
        $this->actingAs($user)->put('/password', ['current_password' => 'password', 'password' => $passphrase, 'password_confirmation' => $passphrase])->assertSessionHasNoErrors();
        $hash = $user->fresh()->password;
        $this->assertStringStartsWith('$argon2id$', $hash);
        $this->assertTrue(Hash::check($passphrase, $hash));
        $this->assertFalse(Hash::check($passphrase.'changed', $hash));
        foreach (['too short', str_repeat('x', 129)] as $bad) {
            $this->put('/password', ['current_password' => $passphrase, 'password' => $bad, 'password_confirmation' => $bad])->assertSessionHasErrorsIn('updatePassword', 'password');
        }
    }
    public function test_legacy_bcrypt_credentials_still_sign_in_and_upgrade_to_argon2id(): void
    {
        $user = User::factory()->create();
        \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update(['password' => password_hash('a legacy long password', PASSWORD_BCRYPT, ['cost' => 4])]);
        $this->post('/login', ['email' => $user->email, 'password' => 'a legacy long password'])->assertSessionHasNoErrors()->assertRedirect(route('userHome'));
        $this->assertStringStartsWith('$argon2id$', $user->fresh()->password);
        $this->get(route('user.profile'))->assertOk();
    }
    public function test_login_errors_are_identical_for_unknown_wrong_password_and_social_accounts(): void
    {
        $user = User::factory()->create();
        $social = User::factory()->create(['provider' => 'google']);
        foreach ([$user->email, $social->email, 'missing@example.test'] as $email) {
            $this->post('/login', ['email' => $email, 'password' => 'incorrect'])->assertSessionHasErrors(['email' => __('auth.failed')]);
            $this->assertGuest('web');
        }
    }
    public function test_unknown_accounts_are_login_throttled_too(): void
    {
        for ($i = 0; $i < 5; $i++) $this->post('/login', ['email' => 'missing@example.test', 'password' => 'incorrect'])->assertSessionHasErrors('email');
        $response = $this->post('/login', ['email' => 'missing@example.test', 'password' => 'incorrect']);
        $response->assertInvalid(['email' => 'Too many login attempts']);
    }
    public function test_registration_has_the_same_response_for_new_and_existing_addresses(): void
    {
        Notification::fake();
        $input = ['name' => 'Customer', 'email' => 'new@example.test', 'password' => 'a long and unique phrase', 'password_confirmation' => 'a long and unique phrase', 'phone' => '09123456789'];
        $this->post('/register', $input)->assertRedirect(route('login'))->assertSessionHas('status', __('auth-security.registration_received'));
        $original = User::where('email', $input['email'])->first()->password;
        $this->post('/register', array_replace($input, ['password' => 'another long secure phrase', 'password_confirmation' => 'another long secure phrase']))
            ->assertRedirect(route('login'))->assertSessionHas('status', __('auth-security.registration_received'));
        $this->assertGuest();
        $this->assertSame($original, User::where('email', $input['email'])->first()->password);
        $this->assertDatabaseCount('users', 1);
    }
    public function test_reset_request_throttle_and_cooldown_do_not_leak_account_existence(): void
    {
        Notification::fake();
        $user = User::factory()->create();
        $this->post('/forgot-password', ['email' => $user->email])->assertSessionHasNoErrors();
        $this->post('/forgot-password', ['email' => $user->email])->assertSessionHasNoErrors();
        Notification::assertSentToTimes($user, CustomerResetPasswordNotification::class, 1);
        $this->post('/forgot-password', ['email' => $user->email]);
        $this->post('/forgot-password', ['email' => $user->email])->assertStatus(429);
    }
    public function test_old_unbound_sessions_and_stale_password_changes_fail_closed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->withSession(['auth_identity' => null])->get('/profile')->assertRedirect(route('login'));
        $stale = $user->fresh();
        AccountSecurity::changePassword($user, 'a different long passphrase');
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        AccountSecurity::changePassword($stale, 'a stale password replacement');
    }

    public function test_email_changes_require_the_current_password_for_customers_and_staff(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->patch('/profile', ['name' => 'Customer', 'email' => 'changed@example.test'])
            ->assertSessionHasErrors('current_password');
        $this->assertSame($user->email, $user->fresh()->email);
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAsMfaAdmin($admin)->post('/admin/profile/update', ['name' => 'Staff', 'email' => 'staff-new@example.test'])
            ->assertSessionHasErrors('current_password');
        $this->assertSame($admin->email, $admin->fresh()->email);
    }

    public function test_admin_password_changes_preserve_leading_and_trailing_passphrase_spaces(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $phrase = '  a long admin passphrase  ';
        $this->actingAsMfaAdmin($admin)->post('/admin/profile/password', ['old_password' => 'password', 'password' => $phrase, 'password_confirmation' => $phrase])->assertSessionHasNoErrors();
        $this->actingAsMfaAdmin($admin->fresh())->post('/admin/profile/password', ['old_password' => $phrase, 'password' => 'another long passphrase', 'password_confirmation' => 'another long passphrase'])->assertSessionHasNoErrors();
    }

    public function test_social_accounts_cannot_start_local_password_recovery_or_password_setup(): void
    {
        $user = User::factory()->create(['provider' => 'google']);
        $this->actingAs($user)->get(route('user.profile'))->assertSee(__('auth-security.provider_managed'));
        $this->post(route('user.password.sendOtp'))->assertForbidden();
        $this->put('/password', ['current_password' => 'password', 'password' => 'another long passphrase', 'password_confirmation' => 'another long passphrase'])->assertForbidden();
    }

    public function test_registration_response_stays_generic_if_verification_delivery_fails(): void
    {
        Notification::partialMock()->shouldReceive('send')->andThrow(new \RuntimeException('Isolated mail transport outage'));
        $this->post('/register', ['name' => 'Customer', 'email' => 'new@example.test', 'password' => 'a long and unique phrase', 'password_confirmation' => 'a long and unique phrase', 'phone' => '09123456789'])
            ->assertRedirect(route('login'))->assertSessionHas('status', __('auth-security.registration_received'));
        $this->assertDatabaseHas('users', ['email' => 'new@example.test']);
    }
}
