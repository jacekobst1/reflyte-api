<?php

declare(strict_types=1);

namespace App\Modules\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Auth\Enums\RoleEnum;
use App\Modules\User\Requests\CreateUserRequest;
use App\Modules\User\Resources\UserResource;
use App\Shared\Response\JsonResp;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();

        return JsonResp::success(
            UserResource::collection($users)
        );
    }

    public function store(CreateUserRequest $data): JsonResponse
    {
        /** @var User $user */
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
        $user->assignRole([RoleEnum::User]);

        return JsonResp::created(['id' => $user->id]);
    }
}
