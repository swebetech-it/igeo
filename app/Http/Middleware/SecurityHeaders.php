<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security headers for SSL/HTTPS
        $headers = [
            // HTTPS Strict Transport Security
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',

            // Content Security Policy
            'Content-Security-Policy' => "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net unpkg.com cdn.tailwindcss.com cdnjs.cloudflare.com; " .
                "style-src 'self' 'unsafe-inline' fonts.googleapis.com fonts.bunny.net cdn.jsdelivr.net cdn.tailwindcss.com cdnjs.cloudflare.com; " .
                "font-src 'self' fonts.gstatic.com fonts.bunny.net cdnjs.cloudflare.com data:; " .
                "img-src 'self' data: https:; " .
                "connect-src 'self'; " .
                "frame-ancestors 'none';",

            // Prevent MIME type sniffing
            'X-Content-Type-Options' => 'nosniff',

            // Prevent clickjacking
            'X-Frame-Options' => 'DENY',

            // XSS Protection
            'X-XSS-Protection' => '1; mode=block',

            // Referrer Policy
            'Referrer-Policy' => 'strict-origin-when-cross-origin',

            // Feature Policy / Permissions Policy
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(self), payment=()',

            // Remove server information
            'Server' => '',
            'X-Powered-By' => '',
        ];

        // Apply headers only in production or when HTTPS is enabled
        if (app()->environment('production') || $request->isSecure()) {
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
        }

        return $response;
    }
}
