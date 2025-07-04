<?php

namespace App\Providers;

use App\Contracts\PaymentGateway;
use App\Services\PaypalGatewayService;
use App\Services\StripeGatewayService;
use Carbon\Laravel\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {

        $this->app->bind(PaymentGateway::class, function ($app) {
            $gateway = config('app.default');
            if ($gateway === 'paypal') {
                return new PaypalGatewayService();
            } elseif ($gateway === 'stripe') {
                return new StripeGatewayService();
            }

            throw new \Exception("Payment gateway [{$gateway}] is not supported.");
        });

        $this->app->bind('payment.paypal', function ($app){
            return new PaypalGatewayService();
        });

        $this->app->bind('payment.stripe', function ($app){
            return new StripeGatewayService();
        });
    }
}