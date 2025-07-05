<?php

namespace App\Services;

use Illuminate\Support\Traits\Macroable;
use App\Services\AnalyticsTracker;

class AnalyticsService
{
    use Macroable;
    
    protected $tracker;
    
    public function __construct(AnalyticsTracker $tracker)
    {
        $this->tracker = $tracker;
    }
    
    public function trackEvent($event)
    {
        return $this->tracker->log($event);
    }
}