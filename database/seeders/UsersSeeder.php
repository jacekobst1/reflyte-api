<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
            'password' => Hash::make('zaq1@WSX'),
        ]);

        $admin->assignRole('admin');
    }
}
