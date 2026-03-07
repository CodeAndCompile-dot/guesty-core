<?php

/*
|--------------------------------------------------------------------------
| Public Web Routes
|--------------------------------------------------------------------------
|
| Routes for the public-facing website: homepage, properties, blogs,
| attractions, contact, newsletter, booking flow, etc.
|
*/

use App\Http\Controllers\ICalController;
use Illuminate\Support\Facades\Route;

// Public routes will be added in Phase 2+
// Route::get('/', [App\Http\Controllers\Public\HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Phase 6: iCal / PriceLabs / Cron Endpoints (public, no auth)
|--------------------------------------------------------------------------
| Legacy: These are unprotected GET routes used by cron jobs and external
| calendar subscribers. We preserve them as-is for backward compatibility.
*/
Route::get('set-cron-job', [ICalController::class, 'setCronJob']);
Route::get('refresh-calendar-data', [ICalController::class, 'refresshCalendar'])->name('refresshCalendar');
Route::get('set-pricelab', [ICalController::class, 'setPriceLab'])->name('setPriceLab');
Route::get('send-welcome-packages', [ICalController::class, 'sendWelcomePackage'])->name('sendWelcomePackage');
Route::get('send-review-email', [ICalController::class, 'sendReviewEmail'])->name('sendReviewEmail');
