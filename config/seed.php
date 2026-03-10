<?php

return [
    'users' => [
        'super' => [
            'email' => env('SEED_SUPER_EMAIL', 'super@laravel-livewire-starter-kit.test'),
            'password' => env('SEED_SUPER_PASSWORD', '12345'),
        ],

        'user' => [
            'email' => env('SEED_USER_EMAIL', 'user@laravel-livewire-starter-kit.test'),
            'password' => env('SEED_USER_PASSWORD', '12345'),
        ],
    ],
];
