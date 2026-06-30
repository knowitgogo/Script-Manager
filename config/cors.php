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
    'paths' => ['api/*', 'user/chatbot/message', 'user/chatBot/message', "*", 'chat/*', 'suggest/*', 'suggest/generate'],
    'allowed_origins' => ['http://localhost:3000', 'http://localhost:3001', 'http://localhost:5173', 'http://127.0.0.1:3000', 'http://127.0.0.1:5500'],
    'allowed_origins_patterns' => [],
    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];