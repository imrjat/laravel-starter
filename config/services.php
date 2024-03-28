<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => env('STRIPE_WEBHOOK_SECRET'),
        'monthly' => env('STRIPE_MONTHLY'),
        'annually' => env('STRIPE_ANNUALLY'),
    ],

];
