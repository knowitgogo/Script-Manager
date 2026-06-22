<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | Configure CORS for your application.
    | To enable CORS, set the `enabled` option to true.
    |
    */

    'enabled' => env('APP_DEBUG', true),
    'paths' => ['api/*', 'user/chatbot/message', 'user/chatBot/message', "*", 'chat/*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];