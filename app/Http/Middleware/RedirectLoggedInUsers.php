<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectLoggedInUsers
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If the user is already logged in...
        if (Auth::check()) {
            
            // 2. Check if they are an Admin using Spatie
            if (Auth::user()->hasRole(config('roles.admin'))) {
                return redirect()->route('admin.dashboard');
            }
            
            // 3. Fallback for any other user type
            return redirect('/');
        }

        // 4. If they are NOT logged in, let them access the login page
        return $next($request);
    }
}