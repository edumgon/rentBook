<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\TenantService;

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

        // Create or find a test user with tenant ID
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'tenant_id' => TenantService::generateTenantId(),
                'name' => 'Test User',
                'provider' => 'test',
                'provider_id' => 'test123',
            ]
        );

        // Ensure user has tenant_id
        if (!$user->tenant_id) {
            $user->tenant_id = TenantService::generateTenantId();
            $user->save();
        }

        Auth::login($user);

        return redirect()->intended(route('dashboard'));
    }
}
