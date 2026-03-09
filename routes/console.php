<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
| Previously cron-triggered via HTTP GET routes. Now managed through the
| Laravel scheduler — run `php artisan schedule:run` every minute via cron.
|
| * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
|
*/

Schedule::command('communication:send-welcome-packages')
    ->dailyAt('07:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/welcome-packages.log'));

Schedule::command('communication:send-reminders')
    ->dailyAt('07:30')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/reminders.log'));

Schedule::command('communication:send-review-requests')
    ->dailyAt('10:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/review-requests.log'));
