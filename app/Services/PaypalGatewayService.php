<?php

namespace App\Services;
use App\Contracts\PaymentGateway;

class PaypalGatewayService implements PaymentGateway
{
    public function charge(float $amount): bool
    {
        // Simulate a successful payment
        return true;
    }
}