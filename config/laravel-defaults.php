<?php

return [
    'log_after_request' => [
        'enabled' => env('LOG_AFTER_REQUEST_ENABLED', true),
        'debug' => env('LOG_AFTER_REQUEST_DEBUG', false),
    ],
    'pwa_url' => env('PWA_URL', config('app.url')),
    'auth' => [
        'password_reset_url_suffix' => 'password-reset?token=%s&email=%s'
    ],
];
