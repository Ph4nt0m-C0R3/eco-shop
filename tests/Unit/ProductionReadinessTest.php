<?php

namespace Tests\Unit;

use App\Support\ProductionReadiness;
use PHPUnit\Framework\TestCase;

class ProductionReadinessTest extends TestCase
{
    private function settings(): array
    {
        return [
            'environment' => 'production', 'debug' => false, 'url' => 'https://shop.acme-commerce.com',
            'key' => 'test-only-key', 'secure_cookie' => true, 'http_only' => true, 'same_site' => 'lax',
            'sessions' => 'database', 'cache' => 'database', 'queue' => 'database',
            'database' => 'mysql', 'database_user' => 'ecoshop_app', 'database_password' => 'test-only-secret',
            'mailer' => 'smtp', 'trusted_proxies' => ['10.0.0.0/24', '2001:db8::/64'], 'private_storage_public' => false,
        ];
    }

    public function test_safe_configuration_passes_without_exposing_credentials(): void
    {
        $this->assertSame([], ProductionReadiness::problems($this->settings()));
    }

    public function test_development_or_incomplete_configuration_is_rejected(): void
    {
        $problems = ProductionReadiness::problems(array_replace($this->settings(), [
            'environment' => 'local', 'debug' => true, 'url' => 'http://localhost',
            'database_user' => 'root', 'database_password' => '', 'secure_cookie' => false,
            'queue' => 'sync', 'mailer' => 'log',
        ]));
        $this->assertGreaterThanOrEqual(8, count($problems));
        $this->assertStringNotContainsString('test-only-secret', implode(' ', $problems));
    }

    public function test_wildcard_proxies_and_public_private_storage_are_rejected(): void
    {
        $problems = ProductionReadiness::problems(array_replace($this->settings(), ['trusted_proxies' => ['*'], 'private_storage_public' => true]));
        $this->assertCount(2, $problems);
        $this->assertNotEmpty(ProductionReadiness::problems(array_replace($this->settings(), ['trusted_proxies' => ['0.0.0.0/0']])));
    }
}
