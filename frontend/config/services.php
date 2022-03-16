<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'xendit' => [
        'api_url' => env('XENDIT_API_URL'),
        'public_key' => env('XENDIT_PUBLIC'),
        'secret_key' => env('XENDIT_SECRET')
    ],
    
    'tokopedia' => [
        'staging_app_id' => 16147,
        'staging_client_id' => '8fc951a023c8431388edcdc65108384d',
        'staging_client_secret' => 'ed800fd9e2094d3ea711a9e3fc08bc92',
        'live_app_id' => '',
        'live_client_id' => '',
        'live_client_secret' => ''
    ],

    'shopee' => [
        'staging_partner_id' => 1006410,
        'staging_key' => '0f35d2cd57f7554d5a1f6cd5115081314b4cbfc9c530853d7aa0e20438c84c23',
        'live_auth_url' => '',
        'live_partner_id' => 1006410,
        'live_key' => '0f35d2cd57f7554d5a1f6cd5115081314b4cbfc9c530853d7aa0e20438c84c23',
    ]
];
