<?php

namespace App\Console\Commands;

use App\Modules\Auth\Enums\RoleEnum;
use App\Modules\User\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $roles = array_map(fn(RoleEnum $role) => $role->value, RoleEnum::cases());

        $name = $this->ask('Name:');
        $email = $this->ask('Email:');
        $password = $this->ask('Password:');
        $role = $this->choice('Role:', $roles);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole($role);

        $this->info("Create user with mail $user->email and role $role.");
    }
}
