<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Auth\Enums\RoleEnum;
use App\Modules\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $testPassword = Hash::make(Config::get('env.test_user_password'));

        // Admin
        $admin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => $testPassword,
        ]);
        $admin->assignRole(RoleEnum::Admin);

        // User
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => $testPassword,
        ]);
        $user->assignRole(RoleEnum::User);

        // User with team
        $userWithTeam = User::create([
            'name' => 'Test User With Team',
            'email' => 'userwt@test.com',
            'password' => $testPassword,
        ]);
        $userWithTeam->assignRole(RoleEnum::User);

        $team = $userWithTeam->ownedTeam()->create([
            'name' => 'Test Team',
        ]);
        $userWithTeam->team()->associate($team);
        $userWithTeam->save();

        // User with team and newsletter
        $userWithTeamAndNewsletter = User::create([
            'name' => 'Test User With Team And Newsletter',
            'email' => 'userwtn@test.com',
            'password' => $testPassword,
        ]);
        $userWithTeamAndNewsletter->assignRole(RoleEnum::User);

        $team = $userWithTeamAndNewsletter->ownedTeam()->create([
            'name' => 'Test Team',
        ]);
        $userWithTeamAndNewsletter->team()->associate($team);
        $userWithTeamAndNewsletter->save();

        $team->newsletter()->create([
            'name' => 'Test Newsletter',
            'description' => 'Test Newsletter Description',
            'esp_name' => 'mailer_lite',
            'esp_api_key' => Str::random()
        ]);
    }
}
