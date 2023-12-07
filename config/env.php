<?php

declare(strict_types=1);

use Illuminate\Support\Str;

return [
    'spa_url' => env('SPA_URL', 'http://app.reflyte.local:3000'),
    'test_user_password' => env('TEST_USER_PASSWORD', Str::random()),
    'test_admin_password' => env('TEST_ADMIN_PASSWORD', Str::random()),
];
