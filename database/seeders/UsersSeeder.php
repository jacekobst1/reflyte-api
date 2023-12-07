<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Auth\Enums\RoleEnum;
use App\Modules\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

final class UsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Jacek',
            'email' => 'jacek@reflyte.com',
            'password' => Hash::make('j'),
        ]);
        $admin->assignRole(RoleEnum::Admin);

        if (app()->isLocal()) {
            $adminForTests = User::create([
                'name' => 'Test Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make(Config::get('env.test_admin_password')),
            ]);
            $adminForTests->assignRole(RoleEnum::Admin);

            $userForTests = User::create([
                'name' => 'Test User',
                'email' => 'user@user.com',
                'password' => Hash::make(Config::get('env.test_user_password')),
            ]);
            $userForTests->assignRole(RoleEnum::User);
        }
    }
}
