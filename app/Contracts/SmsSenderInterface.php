<?php
// app/Contracts/SmsSenderInterface.php (Interface)
namespace App\Contracts;

interface SmsSenderInterface
{
    public function send(string $to, string $message): bool;
}

// app/Services/TwilioSmsSender.php (Concrete Implementation)
namespace App\Services;

use App\Contracts\SmsSenderInterface;
use Illuminate\Support\Facades\Log; // For example logging

class TwilioSmsSender implements SmsSenderInterface
{
    protected $apiKey;
    protected $apiSecret;

    public function __construct(string $apiKey, string $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    public function send(string $to, string $message): bool
    {
        // In a real app, you'd integrate with Twilio's SDK here
        Log::info("Sending SMS via Twilio to {$to}: {$message}");
        Log::info("Using API Key: {$this->apiKey}");
        // ... actual Twilio API call ...
        return true; // Simulate success
    }
}