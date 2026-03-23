<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class SimpleAuthenticationTest extends TestCase
{
    /**
     * Test that welcome page loads.
     */
    public function test_welcome_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Book Lending Manager');
    }

    /**
     * Test that test login works in local environment.
     */
    public function test_test_login_creates_user_and_authenticates(): void
    {
        // Only run this test in local environment
        if (!app()->environment('local')) {
            $this->markTestSkipped('Test login only available in local environment');
        }

        $response = $this->get('/test-login');

        $response->assertRedirect('/dashboard');
        
        // Check that user was created
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'provider' => 'test',
        ]);
    }

    /**
     * Test that dashboard shows user info when authenticated.
     */
    public function test_dashboard_shows_user_info(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'provider' => 'test',
            'provider_id' => 'test123',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Test User');
        $response->assertSee('Welcome to Your Dashboard');
    }

    /**
     * Test logout functionality.
     */
    public function test_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
