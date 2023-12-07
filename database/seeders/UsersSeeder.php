<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Auth\Enums\RoleEnum;
use App\Modules\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Jacek',
            'email' => 'jacek@reflyte.com',
            'password' => Hash::make('j'),
        ]);
        $admin->assignRole(RoleEnum::Admin);
    }
}
