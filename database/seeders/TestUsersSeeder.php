<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Auth\Enums\RoleEnum;
use App\Modules\Newsletter\Newsletter;
use App\Modules\Team\Team;
use App\Modules\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\UuidInterface;

final class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $testPassword = Hash::make(Config::get('env.test_user_password'));

        // Admin
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => $testPassword,
        ]);
        $admin->assignRole(RoleEnum::Admin);

        // User
        $user = User::factory()->create([
            'email' => 'user@test.com',
            'password' => $testPassword,
        ]);
        $user->assignRole(RoleEnum::User);

        // User with team
        $userWithTeam = User::factory()->create([
            'email' => 'userwt@test.com',
            'password' => $testPassword,
        ]);
        $userWithTeam->assignRole(RoleEnum::User);
        $this->createTeam($userWithTeam);

        // User with team and newsletter
        $userWithTeamAndNewsletter = User::factory()->create([
            'email' => 'userwtn@test.com',
            'password' => $testPassword,
        ]);
        $userWithTeamAndNewsletter->assignRole(RoleEnum::User);
        $team = $this->createTeam($userWithTeamAndNewsletter);
        $this->createNewsletter($team->id);
    }

    private function createTeam(User $user): Team
    {
        $team = Team::factory()->create([
            'owner_user_id' => $user->id,
        ]);

        $user->team()->associate($team);
        $user->save();

        return $team;
    }

    private function createNewsletter(UuidInterface $teamId): Newsletter
    {
        return Newsletter::factory()->create([
            'team_id' => $teamId,
        ]);
    }
}
