<?php

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Authentication routes matching the legacy routing structure.
| Login URL: /client-login/login (named 'login')
| Logout: POST /logout
|
*/

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Disable default /login path (legacy behavior)
Route::get('login', function () {
    abort(404);
});

// Custom login at /client-login/login — this MUST be named 'login'
// so the auth middleware can redirect to it
Route::middleware('guest')->group(function () {
    Route::get('client-login/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('client-login', [LoginController::class, 'login']);
});
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
