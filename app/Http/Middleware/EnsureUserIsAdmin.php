<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->hasRole(config('roles.admin'))) {
            // Pro approach: Return a hard 403 Forbidden instead of a messy redirect loop
            abort(403, 'Unauthorized action.'); 
        }

        return $next($request);
    }
}