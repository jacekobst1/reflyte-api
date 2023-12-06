<?php

declare(strict_types=1);

namespace Tests\Helpers;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait SanctumTrait
{
    private function actAsAdmin(): void
    {
        $adminForTests = User::whereEmail('admin@admin.com')->first();

        Sanctum::actingAs($adminForTests);
    }

    private function actAsUser(): void
    {
        $userForTests = User::whereEmail('user@user.com')->first();

        Sanctum::actingAs($userForTests);
    }
}