<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if HTTPS should be forced based on environment
        $forceHttps = config('app.env') === 'production' || env('FORCE_HTTPS', false);

        if ($forceHttps) {
            // Check if the request is not secure and not from a trusted proxy
            if (!$request->isSecure() && !$this->isTrustedProxy($request)) {
                // Redirect to HTTPS version of the same URL
                return redirect()->secure($request->getRequestUri(), 301);
            }
        }

        return $next($request);
    }

    /**
     * Check if the request is from a trusted proxy
     */
    private function isTrustedProxy(Request $request): bool
    {
        // Add logic to check if request is from trusted proxy (like CloudFlare, AWS ELB, etc.)
        $trustedProxies = config('trustedproxy.proxies', []);

        if (empty($trustedProxies)) {
            return false;
        }

        $clientIp = $request->getClientIp();

        foreach ((array) $trustedProxies as $proxy) {
            if ($proxy === '*' || $proxy === $clientIp) {
                return true;
            }
        }

        return false;
    }
}
