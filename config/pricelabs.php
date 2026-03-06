<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PriceLabs API
    |--------------------------------------------------------------------------
    |
    | Dynamic pricing integration with PriceLabs.
    |
    */

    'api_url' => env('PRICELABS_API_URL', 'https://api.pricelabs.co/v1'),
    'access_token' => env('PRICELABS_ACCESS_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Sync Settings
    |--------------------------------------------------------------------------
    */

    'sync_enabled' => env('PRICELABS_SYNC_ENABLED', true),

];
