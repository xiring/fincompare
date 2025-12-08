<?php

namespace Src\Shared\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to require JSON requests for admin API routes
 * This allows the Vue SPA route to catch page loads while API routes handle AJAX calls
 */
class RequireJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For GET requests, only proceed if request wants JSON (AJAX calls)
        // POST/PATCH/DELETE requests are always API calls, so allow them
        if ($request->isMethod('GET') && !$request->wantsJson()) {
            abort(404);
        }

        return $next($request);
    }
}

