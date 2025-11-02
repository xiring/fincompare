<?php
namespace Src\Shared\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Clickjacking protection
        $response->headers->set('X-Frame-Options', 'DENY');
        // MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        // Basic XSS protection header (modern browsers ignore 1; set to 0 to disable old filters)
        $response->headers->set('X-XSS-Protection', '0');
        // Referrer policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        // Permissions Policy (formerly Feature-Policy)
        $response->headers->set('Permissions-Policy', "geolocation=(), microphone=(), camera=(), payment=(), interest-cohort=()");

        // HSTS for HTTPS in production (only if request is secure)
        if (app()->isProduction() && $request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
        }

        // Content Security Policy (loose in local, stricter in production)
        if (app()->isProduction()) {
            $csp = "default-src 'self'; ".
                   "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; ".
                   "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.bunny.net; ".
                   "img-src 'self  https://via.placeholder.com data: blob:; ".
                   "font-src 'self' data: https://fonts.bunny.net; ".
                   "connect-src 'self'; ".
                   "frame-ancestors 'none'; ".
                   "base-uri 'self'; ".
                   "form-action 'self'; ".
                   "upgrade-insecure-requests";
            $response->headers->set('Content-Security-Policy', $csp);
        }

        return $response;
    }
}


