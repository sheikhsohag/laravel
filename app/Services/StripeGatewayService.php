<?php

namespace App\Services;

use App\Contracts\PaymentGateway;

class StripeGatewayService implements PaymentGateway
{
    public function charge(float $amount): bool
    {
        return true;
    }
}