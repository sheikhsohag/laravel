<?php

namespace App\Services;

class LoggerService{
    public function log(string $message): void
    {
        echo "Log: " . $message . '\n';
    }
}