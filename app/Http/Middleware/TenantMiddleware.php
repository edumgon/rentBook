<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Set tenant context for the authenticated user
        if (Auth::check()) {
            $user = Auth::user();
            
            // Here you would typically set the tenant context
            // For now, we'll just continue - this will be implemented later
            // when we create the multi-tenant system
        }

        return $next($request);
    }
}
