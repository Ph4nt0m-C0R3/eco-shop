<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class DeploymentSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'app.env' => 'production', 'app.debug' => false,
            'app.url' => 'https://shop.example.test',
            'deployment.trusted_proxies' => [], 'session.secure' => true, 'session.encrypt' => true,
        ]);
    }

    public function test_https_responses_have_security_headers(): void
    {
        $this->get('https://shop.example.test/up')->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Strict-Transport-Security', 'max-age=31536000');
    }

    public function test_stricter_oauth_referrer_policy_is_preserved_in_production(): void
    {
        config(['services.google.client_id' => 'isolated-client-id', 'services.google.client_secret' => 'isolated-client-secret',
            'services.google.redirect' => 'https://shop.example.test/auth/google/callback']);
        $this->get('https://shop.example.test/auth/google/redirect')->assertStatus(302)
            ->assertHeader('Referrer-Policy', 'no-referrer')->assertHeader('Strict-Transport-Security');
    }

    public function test_unsafe_production_configuration_fails_closed(): void
    {
        config(['app.debug' => true]);
        $this->get('https://shop.example.test/up')->assertStatus(503)
            ->assertSee('Service configuration unavailable.')->assertDontSee('Exception trace');
    }

    public function test_http_browsing_redirects_to_the_configured_https_domain(): void
    {
        $this->get('http://shop.example.test/up?probe=1')->assertStatus(308)
            ->assertHeader('Location', 'https://shop.example.test/up?probe=1');
    }

    public function test_insecure_writes_do_not_execute_application_actions(): void
    {
        $executed = false;
        Route::post('/deployment-write-probe', function () use (&$executed) {
            $executed = true;
            return 'written';
        });
        $this->post('http://shop.example.test/deployment-write-probe')->assertStatus(400);
        $this->assertFalse($executed);
    }

    public function test_an_unapproved_host_is_rejected(): void
    {
        $this->get('https://attacker.example.test/up')->assertStatus(400);
    }

    public function test_untrusted_forwarding_headers_cannot_claim_https(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.5'])
            ->get('http://shop.example.test/up', ['X-Forwarded-Proto' => 'https'])->assertStatus(308);
    }

    public function test_configured_tls_proxy_is_trusted_without_trusting_forwarded_hosts(): void
    {
        config(['deployment.trusted_proxies' => ['10.0.0.0/24']]);
        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.5'])
            ->get('http://shop.example.test/up', [
                'X-Forwarded-Proto' => 'https', 'X-Forwarded-Host' => 'attacker.example.test',
            ])->assertOk()->assertHeader('Strict-Transport-Security');
    }

    public function test_https_customer_session_cookie_is_secure_and_http_only(): void
    {
        config(['session.driver' => 'database']);
        $response = $this->get('https://shop.example.test/login')->assertOk();
        $cookie = collect($response->headers->getCookies())->first(fn ($cookie) => str_contains($cookie->getName(), 'session'));
        $this->assertNotNull($cookie);
        $this->assertTrue($cookie->isSecure());
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertSame('lax', $cookie->getSameSite());
    }

    public function test_production_error_pages_hide_exception_details(): void
    {
        Route::get('/deployment-error-probe', fn () => throw new \RuntimeException('private-diagnostic-marker'));
        $this->get('https://shop.example.test/deployment-error-probe')->assertStatus(500)
            ->assertDontSee('private-diagnostic-marker')->assertDontSee('Exception trace');
    }
}
