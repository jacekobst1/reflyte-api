<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Auth\Enums\RoleEnum;
use App\Modules\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

final class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make(Config::get('env.test_admin_password')),
        ]);
        $admin->assignRole(RoleEnum::Admin);

        // User
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => Hash::make(Config::get('env.test_user_password')),
        ]);
        $user->assignRole(RoleEnum::User);

        // User with team
        $userWithTeam = User::create([
            'name' => 'Test User With Team',
            'email' => 'userwt@test.com',
            'password' => Hash::make(Config::get('env.test_user_password')),
        ]);
        $userWithTeam->assignRole(RoleEnum::User);

        $team = $userWithTeam->ownedTeam()->create([
            'name' => 'Test Team',
        ]);
        $userWithTeam->team()->associate($team);
        $userWithTeam->save();
    }
}
