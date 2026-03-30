<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestFilter
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->boolean('visible')) {
            return $next($request);
        }
      abort(403, 'Page not found.'); 
        return response('Work in progress', Response::HTTP_SERVICE_UNAVAILABLE);
    }
}
