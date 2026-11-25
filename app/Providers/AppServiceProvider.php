<?php

namespace App\Providers;

use App\Models\Service;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share services data safely once per request across views
        View::composer('*', function ($view) {
            $services = once(function () {
                try {
                    return Service::orderBy('name')->get();
                } catch (\Throwable $e) {
                    return collect();
                }
            });

            if (!array_key_exists('services', $view->getData())) {
                $view->with('services', $services);
            }
        });

        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
