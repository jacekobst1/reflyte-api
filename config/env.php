<?php

declare(strict_types=1);

use Illuminate\Support\Str;

return [
    'app_url' => env('APP_URL', 'https://api.reflyte.com'),
    'api_url' => env('API_URL', 'https://api.reflyte.com/api'),
    'spa_url' => env('SPA_URL', 'https://app.reflyte.com'),
    'join_url' => env('JOIN_URL', 'https://reflyte.com/join'),
    'admin_password' => env('ADMIN_PASSWORD', Str::random()),
    'test_user_password' => env('TEST_USER_PASSWORD', Str::random()),
    'admin_ips' => explode(',', env('ADMIN_IPS', '')),
];
