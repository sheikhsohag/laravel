<?php
// app/Contracts/SmsSenderInterface.php (Interface)
namespace App\Contracts;

interface SmsSenderInterface
{
    public function send(string $to, string $message): bool;
}

// app/Services/TwilioSmsSender.php (Concrete Implementation)
