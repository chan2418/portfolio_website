<?php

return [

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'captcha' => [
        'enabled' => env('CAPTCHA_ENABLED', false),
        'provider' => env('CAPTCHA_PROVIDER', 'hcaptcha'),
        'hcaptcha' => [
            'site_key' => env('HCAPTCHA_SITE_KEY'),
            'secret' => env('HCAPTCHA_SECRET_KEY'),
        ],
        'recaptcha' => [
            'site_key' => env('RECAPTCHA_SITE_KEY'),
            'secret' => env('RECAPTCHA_SECRET_KEY'),
        ],
    ],

    'analytics' => [
        'ga_measurement_id' => env('GA_MEASUREMENT_ID'),
        'search_console_verification' => env('SEARCH_CONSOLE_VERIFICATION'),
    ],

];
