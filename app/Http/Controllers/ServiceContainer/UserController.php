<?php

// app/Http/Controllers/UserController.php
namespace App\Http\Controllers\ServiceContainer;

use App\Contracts\SmsSenderInterface; // Import the interface
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $smsSender;

    // Laravel's Service Container injects the concrete TwilioSmsSender here
    public function __construct(SmsSenderInterface $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    public function register(Request $request)
    {
        // ... user registration logic ...

        $userPhone = $request->input('phone');
        $verificationCode = rand(1000, 9999); // Dummy code

        $this->smsSender->send($userPhone, "Your verification code is: " . $verificationCode);

        return response()->json(['message' => 'User registered and SMS sent.']);
    }
}