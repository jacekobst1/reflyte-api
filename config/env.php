<?php

declare(strict_types=1);

use Illuminate\Support\Str;

return [
    'app_url' => env('APP_URL', 'http://reflyte.local'),
    'api_url' => env('API_URL', 'http://reflyte.local/api'),
    'spa_url' => env('SPA_URL', 'http://app.reflyte.local:3000'),
    'admin_password' => env('ADMIN_PASSWORD', Str::random()),
    'test_user_password' => env('TEST_USER_PASSWORD', Str::random()),
    'admin_ips' => explode(',', env('ADMIN_IPS')),
];
