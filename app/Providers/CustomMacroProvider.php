<?php

namespace App\Providers;

use App\Services\AnalyticsService;
use Illuminate\Support\ServiceProvider;

class CustomMacroProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        AnalyticsService::macro('trackPurchase', function ($amount) {
            return $this->trackEvent("purchase:{$amount}");
        });
        
        AnalyticsService::macro('trackPageView', function ($page) {
            return $this->trackEvent("view:{$page}");
        });
    }
}
