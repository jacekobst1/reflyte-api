<?php

declare(strict_types=1);

use Illuminate\Support\Str;

return [
    'app_url' => env('APP_URL', 'http://reflyte.local'),
    'api_url' => env('API_URL', 'http://reflyte.local/api'),
    'spa_url' => env('SPA_URL', 'http://app.reflyte.local:3000'),
    'test_user_password' => env('TEST_USER_PASSWORD', Str::random()),
];
