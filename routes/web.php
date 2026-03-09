<?php

use Illuminate\Support\Facades\Route;

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
require __DIR__.'/web/admin.php';
require __DIR__.'/web/booking.php';
require __DIR__.'/web/public.php'; // Must be last — contains catch-all {seo_url} route
