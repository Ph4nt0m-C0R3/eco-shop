<?php

namespace Tests\Feature\Auth;

use App\Auth\VerifiedGithubProvider;
use App\Models\User;
use App\Support\AccountSecurity;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Tests\TestCase;

class SocialLoginTest extends TestCase
{
    use RefreshDatabase;

    private array $history = [];

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.url' => 'http://localhost']);
        \Illuminate\Support\Facades\URL::forceRootUrl('http://localhost');
        foreach (['google', 'github'] as $provider) config([
            "services.{$provider}.client_id" => 'isolated-client-id',
            "services.{$provider}.client_secret" => 'isolated-client-secret',
            "services.{$provider}.redirect" => "http://localhost/auth/{$provider}/callback",
        ]);
        Socialite::forgetDrivers();
    }

    private function mockProvider(string $provider, array $user = [], array $emails = []): void
    {
        $user = array_replace($provider === 'google' ? [
            'sub' => '123', 'email' => 'social@example.test', 'email_verified' => true,
            'name' => 'Social customer', 'picture' => 'https://lh3.googleusercontent.com/avatar',
        ] : [
            'id' => 456, 'node_id' => 'isolated-node-id', 'login' => 'shopper', 'name' => 'Social customer',
            'email' => 'untrusted-public@example.test', 'avatar_url' => 'https://avatars.githubusercontent.com/u/456',
        ], $user);
        $responses = [$this->providerResponse(['access_token' => 'isolated-provider-token', 'token_type' => 'bearer']), $this->providerResponse($user)];
        if ($provider === 'github') $responses[] = $this->providerResponse($emails ?: [['email' => 'social@example.test', 'primary' => true, 'verified' => true]]);
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $http = new Client(['handler' => $stack]);
        Socialite::extend($provider, fn ($app) => (new ($provider === 'google' ? GoogleProvider::class : VerifiedGithubProvider::class)(
            $app['request'], 'isolated-client-id', 'isolated-client-secret', "http://localhost/auth/{$provider}/callback"
        ))->setHttpClient($http));
        Socialite::forgetDrivers();
    }

    private function providerResponse(array $body): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode($body));
    }

    private function begin(string $provider): string
    {
        $response = $this->get("/auth/{$provider}/redirect")->assertStatus(302);
        parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY), $query);
        $this->assertNotEmpty($query['state']);
        $this->assertSame("http://localhost/auth/{$provider}/callback", $query['redirect_uri']);
        $this->assertSame($provider, session('oauth_context.provider'));
        $this->assertSame($query['state'], session('state'));
        $this->assertSame($provider === 'google' ? 'openid profile email' : 'user:email', $query['scope']);
        // HTTP applications resolve a fresh provider/request at each callback.
        Socialite::forgetDrivers();
        return $query['state'];
    }

    private function completeOAuth(string $provider, string $state, array $extra = [])
    {
        return $this->get("/auth/{$provider}/callback?".http_build_query(array_merge(['state' => $state, 'code' => 'isolated-code'], $extra)));
    }

    public function test_google_and_github_create_verified_customers_and_rotate_bound_sessions(): void
    {
        foreach (['google', 'github'] as $provider) {
            $this->mockProvider($provider);
            $state = $this->begin($provider);
            $sessionId = session()->getId();
            $response = $this->completeOAuth($provider, $state)->assertRedirect(route('userHome'))->assertSessionHasNoErrors();
            $user = Auth::guard('web')->user();
            $this->assertSame($provider, $user->provider);
            $this->assertSame('user', $user->role);
            $this->assertTrue($user->hasVerifiedEmail());
            $this->assertNull($user->password);
            $this->assertNull($user->provider_token);
            $this->assertSame(AccountSecurity::fingerprint($user), session('auth_identity'));
            $this->assertNotSame($sessionId, session()->getId());
            $response->assertSessionMissing('state')->assertSessionMissing('oauth_context');
            $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
            $this->assertSame('no-referrer', $response->headers->get('Referrer-Policy'));
            $this->get('/user/profile')->assertOk();
            Auth::guard('web')->logout();
            $user->delete();
        }
    }

    public function test_unknown_providers_and_unconfigured_services_fail_without_server_errors(): void
    {
        $this->get('/auth/facebook/redirect')->assertNotFound();
        $this->get('/auth/facebook/callback')->assertNotFound();
        config(['services.google.client_secret' => '']);
        $this->get('/auth/google/redirect')->assertRedirect(route('login'))->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_missing_forged_expired_and_cross_provider_state_never_contacts_provider(): void
    {
        $this->mockProvider('google');
        $this->completeOAuth('google', 'unsolicited')->assertSessionHasErrors('email');
        $state = $this->begin('google');
        $this->completeOAuth('google', 'forged')->assertSessionHasErrors('email')->assertSessionMissing('state');
        $this->completeOAuth('google', $state)->assertSessionHasErrors('email');
        $state = $this->begin('google');
        $this->completeOAuth('github', $state)->assertSessionHasErrors('email');
        $state = $this->begin('google');
        $this->travel(11)->minutes();
        $this->completeOAuth('google', $state)->assertSessionHasErrors('email');
        $this->assertCount(0, $this->history);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_provider_cancellation_and_missing_code_clear_flow_without_contacting_provider(): void
    {
        $this->mockProvider('google');
        $state = $this->begin('google');
        $this->completeOAuth('google', $state, ['error' => 'access_denied'])->assertRedirect(route('login'))->assertSessionMissing('state');
        $state = $this->begin('google');
        $this->completeOAuth('google', $state, ['code' => ''])->assertSessionHasErrors('email');
        $this->assertCount(0, $this->history);
    }

    public function test_successful_callback_cannot_be_replayed_after_logout(): void
    {
        $this->mockProvider('google');
        $state = $this->begin('google');
        $this->completeOAuth('google', $state)->assertRedirect(route('userHome'));
        $this->post('/logout');
        $this->completeOAuth('google', $state)->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertCount(2, $this->history);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_google_unverified_missing_and_invalid_email_claims_are_rejected(): void
    {
        foreach ([['email_verified' => false], ['email_verified' => 'false'], ['email_verified' => null], ['email' => null], ['email' => 'invalid'], ['sub' => '']] as $claims) {
            $this->mockProvider('google', $claims);
            $state = $this->begin('google');
            $this->completeOAuth('google', $state)->assertSessionHasErrors('email');
            $this->assertGuest();
            $this->assertDatabaseCount('users', 0);
        }
    }

    public function test_github_public_or_unverified_email_is_not_accepted(): void
    {
        foreach ([[['email' => 'public@example.test', 'primary' => true, 'verified' => false]], [['email' => 'secondary@example.test', 'primary' => false, 'verified' => true]]] as $emails) {
            $this->mockProvider('github', [], $emails);
            $this->completeOAuth('github', $this->begin('github'))->assertSessionHasErrors('email');
            $this->assertGuest();
            $this->assertDatabaseCount('users', 0);
        }
    }

    public function test_existing_identity_uses_provider_id_even_when_provider_email_changes(): void
    {
        $user = User::factory()->create(['provider' => 'google', 'provider_id' => '123', 'email' => 'original@example.test']);
        $other = User::factory()->create(['email' => 'social@example.test']);
        $this->mockProvider('google');
        $this->completeOAuth('google', $this->begin('google'))->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user, 'web');
        $this->assertSame('original@example.test', $user->fresh()->email);
        $this->assertDatabaseCount('users', 2);
        $this->assertSame('local', $other->fresh()->provider);
    }

    public function test_email_collision_never_links_local_other_provider_or_mismatched_id_accounts(): void
    {
        foreach ([['provider' => 'local'], ['provider' => 'github', 'provider_id' => '456'], ['provider' => 'google', 'provider_id' => 'different']] as $account) {
            $user = User::factory()->create(array_merge($account, ['email' => 'Social@Example.Test']));
            $this->mockProvider('google');
            $this->completeOAuth('google', $this->begin('google'))->assertSessionHasErrors('email');
            $this->assertGuest();
            $this->assertSame($account['provider'], $user->fresh()->provider);
            $this->assertDatabaseCount('users', 1);
            $user->delete();
        }
    }

    public function test_staff_provider_identity_cannot_enter_either_portal_via_social_login(): void
    {
        foreach (['admin', 'superadmin'] as $role) {
            $user = User::factory()->create(['role' => $role, 'provider' => 'google', 'provider_id' => '123']);
            $this->mockProvider('google');
            $this->completeOAuth('google', $this->begin('google'))->assertSessionHasErrors('email');
            $this->assertGuest('web');
            $this->assertGuest('admin');
            $user->delete();
        }
    }

    public function test_admin_guard_is_preserved_when_customer_completes_oauth(): void
    {
        $staff = User::factory()->create(['role' => 'superadmin']);
        Auth::guard('admin')->setUser($staff);
        $this->mockProvider('github');
        $this->completeOAuth('github', $this->begin('github'))->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($staff, 'admin');
        $this->assertSame('github', Auth::guard('web')->user()->provider);
    }

    public function test_existing_customer_cannot_switch_identity_through_callback(): void
    {
        $customer = User::factory()->create();
        $this->actingAs($customer)->get('/auth/google/redirect')->assertRedirect(route('userHome'));
        $this->get('/auth/google/callback?code=unused&state=unused')->assertRedirect(route('userHome'));
        $this->assertAuthenticatedAs($customer, 'web');
    }

    public function test_callback_url_mismatch_is_rejected_and_alternate_host_start_is_canonicalized(): void
    {
        config(['app.url' => 'http://127.0.0.1:8002', 'services.google.redirect' => 'http://127.0.0.1:8002/auth/google/callback']);
        $this->get('http://localhost:8002/auth/google/redirect')->assertRedirect('http://127.0.0.1:8002/auth/google/redirect');
        $this->assertNull(session('state'));
        config(['services.google.redirect' => 'https://attacker.example/auth/google/callback']);
        $this->get('http://127.0.0.1:8002/auth/google/redirect')->assertSessionHasErrors('email');
    }

    public function test_provider_identity_changes_revoke_existing_session(): void
    {
        $user = User::factory()->create(['provider' => 'google', 'provider_id' => '123']);
        $this->actingAs($user);
        $original = AccountSecurity::fingerprint($user);
        $user->update(['provider_id' => '456']);
        $this->withSession(['auth_identity' => $original])->get('/profile')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_provider_errors_and_unique_constraint_races_fail_without_exposing_secrets(): void
    {
        $this->mockProvider('google');
        $state = $this->begin('google');
        Log::spy();
        User::creating(fn () => throw new \RuntimeException('isolated-token-must-not-be-logged'));
        $this->completeOAuth('google', $state)->assertRedirect(route('login'))->assertSessionHasErrors('email');
        Log::shouldHaveReceived('warning')->once()->with('OAuth sign-in failed.', ['provider' => 'google', 'exception_type' => \RuntimeException::class]);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_legacy_provider_tokens_are_neither_serialized_nor_mass_assignable(): void
    {
        $user = User::factory()->create();
        DB::table('users')->where('id', $user->id)->update(['provider_token' => 'isolated-legacy-token']);
        $this->assertArrayNotHasKey('provider_token', $user->fresh()->toArray());
        $this->assertFalse($user->isFillable('provider_token'));
    }
}
