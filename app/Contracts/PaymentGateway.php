<?php

namespace App\Contracts;

interface PaymentGateway{

    public function charge(float $amount): bool;
}