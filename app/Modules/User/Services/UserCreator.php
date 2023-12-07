<?php

declare(strict_types=1);

namespace App\Modules\User\Services;

use App\Modules\Auth\Enums\RoleEnum;
use App\Modules\User\Requests\CreateUserRequest;
use App\Modules\User\User;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\UuidInterface;

final class UserCreator
{
    public function createUser(CreateUserRequest $data): UuidInterface
    {
        /** @var User $user */
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
        $user->assignRole([RoleEnum::User]);

        return $user->id;
    }
}
