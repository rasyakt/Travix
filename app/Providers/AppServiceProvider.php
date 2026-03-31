<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('midtrans-webhook', function (Request $request) {
            return [
                Limit::perMinute(240)->by($request->ip()),
                Limit::perMinute(480)->by('midtrans-webhook-global'),
            ];
        });

        if (str_contains(config('app.url'), 'ngrok-free.dev')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
