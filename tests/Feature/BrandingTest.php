<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_identifies_version_two_in_title_navigation_and_footer(): void
    {
        foreach (['en', 'mm'] as $locale) {
            $response = $this->withSession(['locale' => $locale])->get('/')->assertOk();
            $response->assertSee('<title>EcoShop v2.0</title>', false)
                ->assertSee('aria-label="EcoShop v2.0"', false)
                ->assertSee('eco-version-badge', false)
                ->assertSee('EcoShop v2.0');
        }
    }

    public function test_customer_and_admin_authentication_pages_identify_version_two(): void
    {
        foreach (['/login', '/register', '/forgot-password', '/admin/login', '/admin/forgot-password'] as $url) {
            $response = $this->get($url)->assertOk();
            $this->assertMatchesRegularExpression('/<title>[^<]*EcoShop v2\.0[^<]*<\/title>/', $response->getContent());
        }
    }
}
