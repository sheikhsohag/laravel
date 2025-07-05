<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class MacroProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Collection::macro('average', function ($array){
            if (empty($array)) {
                return 0;
            }
            $sum = array_sum($array);
            $count = count($array);
            return $sum / $count;
        });
    }
}
