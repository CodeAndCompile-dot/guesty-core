<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Guesty Open API
    |--------------------------------------------------------------------------
    |
    | OAuth2 client credentials for the Guesty Open API (open-api.guesty.com).
    |
    */

    'client_id' => env('GUESTY_CLIENT_ID', ''),
    'client_secret' => env('GUESTY_CLIENT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Guesty Booking Engine API
    |--------------------------------------------------------------------------
    |
    | OAuth2 credentials for the Guesty Booking Engine (booking.guesty.com).
    |
    */

    'booking_client_id' => env('GUESTY_BOOKING_CLIENT_ID', ''),
    'booking_client_secret' => env('GUESTY_BOOKING_CLIENT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    */

    'open_api_url' => env('GUESTY_OPEN_API_URL', 'https://open-api.guesty.com/v1'),
    'booking_api_url' => env('GUESTY_BOOKING_API_URL', 'https://booking.guesty.com'),
    'pay_url' => env('GUESTY_PAY_URL', 'https://pay.guesty.com/api/tokenize/v2'),

    /*
    |--------------------------------------------------------------------------
    | Token Management
    |--------------------------------------------------------------------------
    |
    | Token TTL in seconds. Guesty tokens typically expire after ~24 hours.
    |
    */

    'token_ttl' => env('GUESTY_TOKEN_TTL', 86400),

    /*
    |--------------------------------------------------------------------------
    | Account ID
    |--------------------------------------------------------------------------
    */

    'account_id' => env('GUESTY_ACCOUNT_ID', ''),

];
