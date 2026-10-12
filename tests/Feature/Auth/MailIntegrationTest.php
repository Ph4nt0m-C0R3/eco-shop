<?php

namespace Tests\Feature\Auth;

use App\Mail\DeliveryTestMail;
use App\Models\User;
use App\Notifications\PasswordChallengeNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MailIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private function configureSmtp(): void
    {
        config(['mail.default' => 'smtp', 'mail.from.address' => 'sender@shop-domain.com',
            'mail.mailers.smtp' => ['transport' => 'smtp', 'host' => 'smtp.shop-domain.com', 'port' => 587,
                'username' => 'isolated-mail-user', 'password' => 'isolated-mail-password', 'require_tls' => true, 'timeout' => 15]]);
    }

    public function test_delivery_test_refuses_offline_mailers_and_invalid_recipients(): void
    {
        Mail::fake();
        $this->artisan('mail:test', ['recipient' => 'owner@shop-domain.com'])->assertFailed();
        $this->configureSmtp();
        foreach (['invalid', 'audit-user@example.test', "owner@shop-domain.com\r\nBcc:someone@shop-domain.com"] as $recipient) {
            $this->artisan('mail:test', ['recipient' => $recipient])->assertFailed();
        }
        Mail::assertNothingSent();
    }

    public function test_delivery_test_sends_only_one_non_sensitive_message_to_explicit_recipient(): void
    {
        $this->configureSmtp();
        Mail::fake();
        $this->artisan('mail:test', ['recipient' => 'owner@shop-domain.com'])
            ->expectsOutput('The mail transport accepted the test. Confirm receipt in the inbox or spam folder.')->assertSuccessful();
        Mail::assertSent(DeliveryTestMail::class, fn ($mail) => $mail->hasTo('owner@shop-domain.com'));
        Mail::assertSentCount(1);
        $html = (new DeliveryTestMail)->render();
        $this->assertStringContainsString('EcoShop v2.0', $html);
        $this->assertStringNotContainsString('isolated-mail-password', $html);
    }

    public function test_delivery_failure_reports_no_credentials_or_provider_exception_details(): void
    {
        $this->configureSmtp();
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('isolated-mail-password smtp-private-host'));
        $this->artisan('mail:test', ['recipient' => 'owner@shop-domain.com'])
            ->expectsOutput('The delivery service rejected the test or could not be reached. Check SMTP/API credentials and network access.')->assertFailed();
    }

    public function test_integration_diagnostics_fail_for_missing_credentials_and_insecure_smtp(): void
    {
        $this->configureSmtp();
        config(['mail.mailers.smtp.require_tls' => false, 'services.google.client_secret' => '', 'services.github.client_id' => '']);
        $this->artisan('integrations:check')->expectsOutput('MAIL_REQUIRE_TLS must be true for real SMTP delivery.')
            ->expectsOutput('GOOGLE client secret is missing.')->expectsOutput('GITHUB client id is missing.')->assertFailed();
    }

    public function test_registration_queues_branded_encrypted_verification_with_a_valid_signed_link(): void
    {
        Notification::fake();
        $this->post('/register', ['name' => 'Customer', 'email' => 'verification@example.test', 'phone' => '09123456789',
            'password' => 'a long and unique passphrase', 'password_confirmation' => 'a long and unique passphrase'])->assertRedirect(route('login'));
        $user = User::where('email', 'verification@example.test')->firstOrFail();
        $this->assertGuest();
        Notification::assertSentTo($user, VerifyEmailNotification::class, function ($notification) use ($user) {
            $this->assertInstanceOf(ShouldQueue::class, $notification);
            $this->assertInstanceOf(ShouldBeEncrypted::class, $notification);
            $this->assertTrue($notification->afterCommit);
            $mail = $notification->toMail($user);
            $this->assertStringContainsString('EcoShop v2.0', $mail->subject);
            $this->actingAs($user)->get($mail->actionUrl)->assertRedirect(route('dashboard', absolute: false).'?verified=1');
            $this->assertTrue($user->fresh()->hasVerifiedEmail());
            return true;
        });
    }

    public function test_delivery_failure_invalidates_new_otp_and_exposes_only_a_generic_error(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        Notification::partialMock()->shouldReceive('send')->withArgs(fn ($notifiable, $notification) => $notification instanceof PasswordChallengeNotification)
            ->andThrow(new \RuntimeException('Isolated SMTP delivery outage'));
        $this->post(route('user.password.sendOtp'))->assertSessionHasErrors('otp')->assertSessionMissing('password_challenge');
        $this->assertDatabaseHas('password_challenges', ['user_id' => $user->id, 'code_hash' => null]);
    }

    public function test_verification_resend_failure_returns_a_visible_generic_error(): void
    {
        $user = User::factory()->unverified()->create();
        Notification::partialMock()->shouldReceive('send')->andThrow(new \RuntimeException('isolated-private-transcript'));
        $this->actingAs($user)->from('/verify-email')->post('/email/verification-notification')
            ->assertRedirect('/verify-email')->assertSessionHasErrors('email');
        $this->withCookie(config('session.customer_cookie'), session()->getId())->get('/verify-email')
            ->assertSee(__('auth-security.delivery_failed'))->assertDontSee('isolated-private-transcript');
    }
}
