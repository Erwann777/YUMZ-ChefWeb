<?php

namespace App\Providers;

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
        // Share CurrencyService globally
        view()->share('cs', app(\App\Services\CurrencyService::class));

        // Dynamically share the authenticated user's currency with all views
        view()->composer('*', function ($view) {
            $user = auth()->user();
            $view->with('viewerCurrency', $user?->currency ?? 'IDR');
        });
    }
}
