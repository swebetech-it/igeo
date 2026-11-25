<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class LogVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        if (!app()->runningUnitTests() && !in_array($ip, ['127.0.0.1', '::1', null], true)) {
            try {
                $agent = new Agent();
                $device = $agent->device();
                $platform = $agent->platform();
                $browser = $agent->browser();

                $country = null;
                $city = null;
                $ctx = stream_context_create([
                    'http' => [
                        'timeout' => 1.5,
                    ],
                ]);
                $raw = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,city", false, $ctx);
                $geo = $raw ? @json_decode($raw, true) : null;
                if ($geo && ($geo['status'] ?? null) === 'success') {
                    $country = $geo['country'] ?? null;
                    $city = $geo['city'] ?? null;
                }

                Visitor::create([
                    'ip' => $ip,
                    'country' => $country,
                    'city' => $city,
                    'device' => $device,
                    'platform' => $platform,
                    'browser' => $browser,
                ]);
            } catch (\Throwable $e) {
                // Never block page rendering if visitor analytics fails
            }
        }

        return $next($request);
    }
}
