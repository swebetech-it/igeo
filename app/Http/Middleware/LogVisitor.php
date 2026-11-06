<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Jenssegers\Agent\Agent;
use App\Models\Visitor;

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
        $agent = new Agent();
        $device = $agent->device();
        $platform = $agent->platform();
        $browser = $agent->browser();

        // Use a free IP geolocation API (ip-api.com)
        $country = null;
        $city = null;
        try {
            $geo = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,city"), true);
            if ($geo && ($geo['status'] ?? null) === 'success') {
                $country = $geo['country'] ?? null;
                $city = $geo['city'] ?? null;
            }
        } catch (\Exception $e) {}

        // Only log if not a local IP
        if ($ip !== '127.0.0.1' && $ip !== '::1') {
            Visitor::create([
                'ip' => $ip,
                'country' => $country,
                'city' => $city,
                'device' => $device,
                'platform' => $platform,
                'browser' => $browser,
            ]);
        }

        return $next($request);
    }
}
