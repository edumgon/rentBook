<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class TenantService
{
    /**
     * Get the current tenant ID for the authenticated user.
     */
    public static function getCurrentTenantId(): ?string
    {
        $user = Auth::user();
        
        return $user ? $user->tenant_id : null;
    }

    /**
     * Set the tenant context for the current request.
     */
    public static function setTenantContext(string $tenantId): void
    {
        // Store tenant ID in session for the request
        session(['current_tenant_id' => $tenantId]);
    }

    /**
     * Generate a unique tenant ID for new users.
     */
    public static function generateTenantId(): string
    {
        return 'tenant_' . uniqid() . '_' . time();
    }

    /**
     * Check if a user belongs to the current tenant.
     */
    public static function isCurrentTenant(?string $tenantId): bool
    {
        return $tenantId === self::getCurrentTenantId();
    }

    /**
     * Ensure a model belongs to the current tenant.
     * Throw exception if not.
     */
    public static function ensureTenantAccess(string $modelTenantId): void
    {
        if (!self::isCurrentTenant($modelTenantId)) {
            abort(403, 'Access denied: You do not have permission to access this resource.');
        }
    }
}
