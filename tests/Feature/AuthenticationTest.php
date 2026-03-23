<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that authentication redirect works for Google.
     */
    public function test_google_auth_redirect(): void
    {
        $response = $this->get('/auth/google');

        $response->assertRedirect();
        $this->assertStringContains('accounts.google.com', $response->getTargetUrl());
    }

    /**
     * Test that authentication redirect works for Facebook.
     */
    public function test_facebook_auth_redirect(): void
    {
        $response = $this->get('/auth/facebook');

        $response->assertRedirect();
        $this->assertStringContains('facebook.com', $response->getTargetUrl());
    }

    /**
     * Test that authentication redirect works for Microsoft.
     */
    public function test_microsoft_auth_redirect(): void
    {
        $response = $this->get('/auth/microsoft');

        $response->assertRedirect();
        $this->assertStringContains('microsoft.com', $response->getTargetUrl());
    }

    /**
     * Test that invalid provider returns 404.
     */
    public function test_invalid_provider_returns_404(): void
    {
        $response = $this->get('/auth/invalid-provider');

        $response->assertStatus(404);
    }

    /**
     * Test that dashboard requires authentication.
     */
    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test that logout works.
     */
    public function test_logout(): void
    {
        // Create a user
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'provider' => 'google',
            'provider_id' => '123456789',
        ]);

        // Act as user
        $this->actingAs($user);

        // Test logout
        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
