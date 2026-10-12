<?php

namespace Tests\Feature\Auth;

use App\Models\PasswordChallenge;
use App\Models\User;
use App\Notifications\PasswordChallengeNotification;
use App\Support\AccountSecurity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordChallengeTest extends TestCase
{
    use RefreshDatabase;

    private function issue(User $user, string $portal = 'user'): string
    {
        Notification::fake();
        $this->withCredentials();
        $portal === 'admin' ? $this->actingAsMfaAdmin($user) : $this->actingAs($user);
        $response = $this->post(route($portal.'.password.sendOtp'))->assertRedirect(route($portal.'.password.otp.verify.form'));
        $cookieName = config($portal === 'admin' ? 'session.admin_cookie' : 'session.customer_cookie');
        $cookie = collect($response->headers->getCookies())->first(fn ($cookie) => $cookie->getName() === $cookieName);
        $this->withUnencryptedCookie($cookieName, $cookie->getValue());
        return Notification::sent($user, PasswordChallengeNotification::class)->last()->code;
    }
    private function resetInput(): array
    {
        return ['password' => 'a secure and long passphrase', 'password_confirmation' => 'a secure and long passphrase'];
    }

    public function test_customer_otp_is_hashed_and_single_use_through_reset(): void
    {
        $user = User::factory()->create();
        $code = $this->issue($user);
        $row = PasswordChallenge::first();
        $this->assertMatchesRegularExpression('/^[0-9]{6}$/', $code);
        $this->assertNotSame($code, $row->code_hash);
        $this->assertTrue(Hash::check($code, $row->code_hash));
        $this->assertDatabaseCount('password_otps', 0);
        $this->post(route('user.password.otp.verify'), ['otp' => $code])->assertRedirect(route('user.password.otp.reset.form'));
        $this->assertNull($row->fresh()->code_hash);
        $this->get(route('user.password.otp.reset.form'))->assertOk();
        $this->post(route('user.password.otp.verify'), ['otp' => $code])->assertSessionHasErrors('otp');
        $this->post(route('user.password.resetOtp'), $this->resetInput())->assertRedirect(route('user.profile'));
        $this->assertTrue(Hash::check($this->resetInput()['password'], $user->fresh()->password));
        $this->assertNotNull($row->fresh()->consumed_at);
        $this->post(route('user.password.resetOtp'), $this->resetInput())->assertForbidden();
    }
    public function test_admin_otp_uses_the_same_bound_single_use_rules(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);
        $code = $this->issue($user, 'admin');
        $this->post(route('admin.password.otp.verify'), ['otp' => $code])->assertRedirect(route('admin.password.otp.reset.form'));
        $this->post(route('admin.password.resetOtp'), $this->resetInput())->assertRedirect(route('admin#profile'));
        $this->get(route('adminDashboard'))->assertRedirect(route('admin.mfa.challenge'));
        $this->assertTrue(Hash::check($this->resetInput()['password'], $user->fresh()->password));
    }
    public function test_expired_otp_and_expired_verified_grant_are_rejected(): void
    {
        $user = User::factory()->create();
        $code = $this->issue($user);
        $this->travel(5)->minutes();
        $this->post(route('user.password.otp.verify'), ['otp' => $code])->assertSessionHasErrors('otp');
        $this->travelBack();
        $this->post(route('user.password.otp.verify'), ['otp' => $code])->assertSessionHasNoErrors();
        $this->travel(5)->minutes();
        $this->post(route('user.password.resetOtp'), $this->resetInput())->assertForbidden();
    }
    public function test_attempts_commit_and_resends_do_not_reset_the_brute_force_budget(): void
    {
        $user = User::factory()->create();
        $this->issue($user);
        for ($i = 0; $i < 4; $i++) $this->post(route('user.password.otp.verify'), ['otp' => 'not-a-code'])->assertSessionHasErrors('otp');
        $this->assertSame(4, PasswordChallenge::first()->attempts);
        $this->postJson(route('user.password.otp.resend'))->assertStatus(429);
        $this->travel(61)->seconds();
        $this->postJson(route('user.password.otp.resend'))->assertOk();
        $newCode = Notification::sent($user, PasswordChallengeNotification::class)->last()->code;
        $this->assertSame(4, PasswordChallenge::first()->attempts);
        $this->post(route('user.password.otp.verify'), ['otp' => 'wrong'])->assertSessionHasErrors('otp');
        $this->assertSame(5, PasswordChallenge::first()->attempts);
        $this->post(route('user.password.otp.verify'), ['otp' => $newCode])->assertSessionHasErrors('otp');
        $this->travel(61)->seconds();
        $this->postJson(route('user.password.otp.resend'))->assertStatus(429);
    }
    public function test_resend_replaces_the_code_and_invalidates_a_verified_grant(): void
    {
        $user = User::factory()->create();
        $old = $this->issue($user);
        $this->post(route('user.password.otp.verify'), ['otp' => $old])->assertSessionHasNoErrors();
        $this->travel(61)->seconds();
        $this->postJson(route('user.password.otp.resend'))->assertOk()->assertSessionMissing('password_grant');
        $this->post(route('user.password.resetOtp'), $this->resetInput())->assertForbidden();
        $this->post(route('user.password.otp.verify'), ['otp' => $old])->assertSessionHasErrors('otp');
        $this->assertSame(1, PasswordChallenge::first()->attempts);
    }
    public function test_hourly_send_limit_survives_the_full_send_resend_loop(): void
    {
        $user = User::factory()->create();
        $this->issue($user);
        for ($i = 0; $i < 4; $i++) {
            $this->travel(61)->seconds();
            $this->post(route('user.password.sendOtp'))->assertRedirect();
        }
        $this->travel(61)->seconds();
        $this->post(route('user.password.sendOtp'))->assertStatus(429);
        $this->assertSame(5, PasswordChallenge::first()->send_count);
    }
    public function test_grant_cannot_be_forged_or_moved_to_another_account(): void
    {
        $user = User::factory()->create();
        $code = $this->issue($user);
        $this->withSession(['otp_verified' => true, 'password_grant' => 'forged']);
        $this->post(route('user.password.resetOtp'), $this->resetInput())->assertForbidden();
        $this->post(route('user.password.otp.verify'), ['otp' => $code])->assertSessionHasNoErrors();
        $other = User::factory()->create();
        $this->unencryptedCookies = [];
        $this->withCookie(config('session.customer_cookie'), \Illuminate\Support\Str::random(40));
        $this->actingAs($other)->post(route('user.password.resetOtp'), $this->resetInput())->assertForbidden();
        $this->assertTrue(Hash::check('password', $other->fresh()->password));
    }
    public function test_otp_cannot_be_moved_to_a_different_browser_session(): void
    {
        $user = User::factory()->create();
        $code = $this->issue($user);
        $this->unencryptedCookies = [];
        $this->withCookie(config('session.customer_cookie'), \Illuminate\Support\Str::random(40));
        $this->post(route('user.password.otp.verify'), ['otp' => $code])->assertSessionHasErrors('otp');
    }
    public function test_password_change_invalidates_grants_and_existing_sessions(): void
    {
        $user = User::factory()->create();
        $code = $this->issue($user);
        $this->post(route('user.password.otp.verify'), ['otp' => $code])->assertSessionHasNoErrors();
        $oldIdentity = AccountSecurity::fingerprint($user);
        AccountSecurity::changePassword($user, 'changed on another device');
        $this->withSession(['auth_identity' => $oldIdentity])->get(route('user.profile'))->assertRedirect(route('login'));
        $this->assertNotNull(PasswordChallenge::first()->consumed_at);
    }
    public function test_email_change_invalidates_the_account_session_and_challenge(): void
    {
        $user = User::factory()->create();
        $this->issue($user);
        $user->update(['email' => 'changed@example.test']);
        $this->get(route('user.password.otp.verify.form'))->assertRedirect(route('login'));
        $this->assertNull($user->fresh()->email_verified_at);
        $this->assertNotNull(PasswordChallenge::first()->consumed_at);
    }
}
