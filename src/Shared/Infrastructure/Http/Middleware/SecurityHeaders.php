<?php

namespace Src\Shared\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * SecurityHeaders class.
 */
class SecurityHeaders
{
    /**
     * Handle the job or request.
     *
     * @return mixed
     */
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
        // Permissions Policy (formerly Feature-Policy). Keep widely supported features only to avoid console warnings.
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=()');

        // HSTS for HTTPS in production (only if request is secure)
        if (app()->isProduction() && $request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
        }

        // Content Security Policy (loose in local, stricter in production)
        // if (app()->isProduction()) {
        //     // Production: no 'unsafe-eval'. Allow inline scripts for our inline Alpine helpers,
        //     // allow external images (partner logos) over HTTPS, and our font/style CDNs.
        //     $csp = "default-src 'self'; ".
        //            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; ".
        //            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.bunny.net; ".
        //            "img-src 'self' https: data: blob: https://placehold.co; ".
        //            "font-src 'self' data: https://fonts.bunny.net; ".
        //            "connect-src 'self' https:; ".
        //            "object-src 'none'; ".
        //            "frame-ancestors 'none'; ".
        //            "base-uri 'self'; ".
        //            "form-action 'self'; ".
        //            'upgrade-insecure-requests';
        //     $response->headers->set('Content-Security-Policy', $csp);
        // } else {
        //     // Local/dev: allow Vite dev tooling (eval, HMR) and websocket connections
        //     $csp = "default-src 'self' blob: data:; ".
        //            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; ".
        //            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.bunny.net; ".
        //            "img-src 'self' https://placehold.co data: blob:; ".
        //            "font-src 'self' data: https://fonts.bunny.net; ".
        //            "connect-src 'self' ws: http://localhost:* http://127.0.0.1:*; ".
        //            "frame-ancestors 'none'; ".
        //            "base-uri 'self'; ".
        //            "form-action 'self'";
        //     $response->headers->set('Content-Security-Policy', $csp);
        // }

        return $response;
    }
}
