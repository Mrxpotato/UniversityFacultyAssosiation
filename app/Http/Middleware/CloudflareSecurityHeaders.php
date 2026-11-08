<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CloudflareSecurityHeaders
{
    /**
     * Security headers optimised for a Cloudflare-proxied Laravel app.
     * These complement (and don't duplicate) headers Cloudflare adds at edge.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent MIME-type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Clickjacking protection (same-origin frames only)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // XSS filter for older browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy — send origin only cross-origin
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy — disable unused browser APIs
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self), payment=()');

        // Strict Transport Security — tell browsers to always use HTTPS
        // Only apply when served over HTTPS (Cloudflare forces this)
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
