<?php

declare(strict_types=1);

namespace Tests\Helpers;

use App\Modules\User\User;
use Laravel\Sanctum\Sanctum;

trait SanctumTrait
{
    private function actAsAdmin(): void
    {
        $adminForTests = User::whereEmail('admin@test.com')->first();

        Sanctum::actingAs($adminForTests);
    }

    private function actAsUser(): void
    {
        $userForTests = User::whereEmail('user@test.com')->first();

        Sanctum::actingAs($userForTests);
    }

    private function actAsUserWithTeam(): void
    {
        $userForTests = User::whereEmail('userwt@test.com')->first();

        Sanctum::actingAs($userForTests);
    }
}
