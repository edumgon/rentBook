<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TestLoginController extends Controller
{
    /**
     * Create a test user and login for development purposes.
     * This should only be used in local development.
     */
    public function testLogin()
    {
        if (app()->environment() !== 'local') {
            abort(403, 'Test login only available in local environment');
        }

        // Create or find a test user
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'provider' => 'test',
                'provider_id' => 'test123',
            ]
        );

        Auth::login($user);

        return redirect()->intended(route('dashboard'));
    }
}
