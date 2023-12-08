<?php

declare(strict_types=1);

namespace Tests\Helpers;

use App\Modules\User\User;
use Laravel\Sanctum\Sanctum;

trait SanctumTrait
{
    private User $loggedUser;

    private function actAsAdmin(): void
    {
        $admin = User::whereEmail('admin@test.com')->first();

        Sanctum::actingAs($admin);
        $this->loggedUser = $admin;
    }

    private function actAsUser(): void
    {
        $user = User::whereEmail('user@test.com')->first();

        Sanctum::actingAs($user);
        $this->loggedUser = $user;
    }

    private function actAsCompleteUser(): void
    {
        $completeUser = User::whereEmail('complete-user@test.com')->first();

        Sanctum::actingAs($completeUser);
        $this->loggedUser = $completeUser;
    }
}
