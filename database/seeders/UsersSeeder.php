<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $admin->assignRole('admin');

        if (app()->isLocal()) {
            $adminForTests = User::create([
                'name' => 'Test Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make(Str::random()),
            ]);
            $adminForTests->assignRole('admin');

            $userForTests = User::create([
                'name' => 'Test User',
                'email' => 'user@user.com',
                'password' => Hash::make(Str::random()),
            ]);
            $userForTests->assignRole('user');
        }
    }
}
