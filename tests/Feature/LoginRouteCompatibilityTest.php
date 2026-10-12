<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRouteCompatibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_account_cannot_sign_in_through_customer_login(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_customer_cannot_sign_in_through_admin_login(): void
    {
        $user = User::factory()->create();
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/admin/login');
        $this->assertGuest();
    }
}
