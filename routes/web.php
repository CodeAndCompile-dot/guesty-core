<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Split Route Files
|--------------------------------------------------------------------------
|
| Load route definitions from separate files for public, admin, and
| booking flows. Each file is independently maintainable.
|
*/

require __DIR__.'/web/auth.php';
require __DIR__.'/web/public.php';
require __DIR__.'/web/admin.php';
require __DIR__.'/web/booking.php';
