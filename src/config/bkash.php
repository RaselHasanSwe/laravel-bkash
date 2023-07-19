<?php

return [
    'website_url'               => env('APP_URL'),
    'bkash_sendbox_base_url'    => env('BKASH_SENDBOX_BASE_URL'),
    'bkash_live_base_url'       => env('BKASH_LIVE_BASE_URL'),
    'bkash_user_name'           => env('BKASH_USER_NAME'),
    'bkash_password'            => env('BKASH_PASSWORD'),
    'bkash_app_key'             => env('BKASH_APP_KEY'),
    'bkash_app_secret'          => env('BKASH_APP_SECRET'),
    'bkash_callback_url'        => env('BKASH_CALLBACK_URL'),
    'bkash_payment_mode'        => env('BKASH_PAYMENT_MODE', 'sendbox')
    // bakash payment mode can be 'sendbox' or 'live'
];
