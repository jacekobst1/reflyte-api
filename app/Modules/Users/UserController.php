<?php

namespace App\Modules\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Users\Requests\CreateUserRequest;
use App\Shared\Response\JsonResp;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();

        return JsonResp::success($users);
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return JsonResp::created(['id' => $user->id]);
    }
}
