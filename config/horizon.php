<?php

return [

    'domain' => env('HORIZON_DOMAIN', null),

    'path' => env('HORIZON_PATH', 'horizon'),

    'use' => env('HORIZON_USE', 'default'),

    'prefix' => env('HORIZON_PREFIX', env('APP_NAME', 'laravel').'_horizon:'),

    'middleware' => ['web','auth'],

    'waits' => [
        'redis:default' => 60,
        'redis:imports' => 60,
    ],

    'trim' => [
        'recent' => 60,
        'pending' => 60,
        'completed' => 60,
        'recent_failed' => 60,
        'failed' => 10080,
        'monitored' => 60,
    ],

    'metrics' => [
        'queue' => 'default',
    ],

    'fast_termination' => false,

    'memory_limit' => 128,

    'environments' => [
        'production' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['default','imports'],
                'balance' => 'simple',
                'minProcesses' => 1,
                'maxProcesses' => 10,
                'balanceMaxShift' => 1,
                'balanceCooldown' => 3,
                'tries' => 3,
            ],
        ],

        'local' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['default','imports'],
                'balance' => 'off',
                'processes' => 3,
                'tries' => 1,
            ],
        ],
    ],
];


