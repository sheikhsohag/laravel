<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Schedule::call(function () {
//     Log::info('this is from scheduler');
// })->everyFiveSeconds();

// Schedule::call(function (){
//     Log::info(
//         "this is second!"
//     );
// })->everyTwoSeconds();

// Artisan::command('log', function () {
//     Log::info('this is second way of scheduler');
// })->purpose('Log in the laravel.log file');

// // Scheduling the command
// Schedule::command('log')->everyTwoSeconds();

Schedule::command('log-info-command')->everySecond();