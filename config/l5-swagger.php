<?php

return [
    'defaults' => [
        'docs' => 'public/api-docs',
        'base_url' => env('APP_URL'),
        'schemes' => ['http'],
        'host' => env('APP_URL'),
        'info' => [
            'title' => 'My API Documentation',
            'version' => '1.0.0',
        ],
    ],
];

