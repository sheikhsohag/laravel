<?php

namespace App\Providers;

use App\Contracts\SmsSenderInterface;
use App\Services\TwilioSmsSender;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the interface to its concrete implementation.
        // When someone asks for SmsSenderInterface, give them TwilioSmsSender.
        $this->app->bind(SmsSenderInterface::class, function ($app) {
            // We resolve dependencies of TwilioSmsSender here.
            // In a real app, these would come from config/services.php or environment variables.
            $apiKey = config('services.twilio.key');
            $apiSecret = config('services.twilio.secret');

            return new TwilioSmsSender($apiKey, $apiSecret);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // You could do something here that needs the container fully ready,
        // e.g., if you had a custom Blade directive related to SMS.
        // For this example, we don't need anything in boot().
    }
}