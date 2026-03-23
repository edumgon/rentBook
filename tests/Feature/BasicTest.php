<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasicTest extends TestCase
{
    /**
     * Test that the welcome page loads correctly.
     */
    public function test_welcome_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Book Lending Manager');
        $response->assertSee('Track your personal book lending with friends');
    }

    /**
     * Test that login page redirects correctly.
     */
    public function test_login_page_redirects(): void
    {
        $response = $this->get('/login');

        $response->assertRedirect('/');
    }

    /**
     * Test that protected routes redirect to login.
     */
    public function test_protected_routes_redirect_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}
