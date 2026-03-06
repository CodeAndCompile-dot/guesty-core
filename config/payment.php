<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Payment Gateway
    |--------------------------------------------------------------------------
    |
    | Supported: "stripe", "paypal"
    |
    */

    'default' => env('PAYMENT_GATEWAY', 'stripe'),

    /*
    |--------------------------------------------------------------------------
    | Stripe
    |--------------------------------------------------------------------------
    */

    'stripe' => [
        'secret_key' => env('STRIPE_SECRET_KEY', ''),
        'publishable_key' => env('STRIPE_PUBLISHABLE_KEY', ''),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | PayPal
    |--------------------------------------------------------------------------
    */

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_CLIENT_SECRET', ''),
        'mode' => env('PAYPAL_MODE', 'sandbox'), // sandbox or live
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    */

    'currency' => env('PAYMENT_CURRENCY', 'USD'),

];
