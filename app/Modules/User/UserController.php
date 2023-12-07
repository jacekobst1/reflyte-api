<?php

declare(strict_types=1);

namespace App\Modules\User;

use App\Http\Controllers\Controller;
use App\Modules\User\Requests\CreateUserRequest;
use App\Modules\User\Resources\UserResource;
use App\Modules\User\Services\UserCreator;
use App\Modules\User\Services\UserGetter;
use App\Shared\Response\JsonResp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getLoggedUser(Request $request, UserGetter $getter): JsonResponse
    {
        $loggedUser = $getter->getLoggedUser($request);

        return JsonResp::success(
            new UserResource($loggedUser)
        );
    }

    public function index(UserGetter $getter): JsonResponse
    {
        $users = $getter->getAll();

        return JsonResp::success(
            UserResource::collection($users)
        );
    }

    public function store(CreateUserRequest $data, UserCreator $creator): JsonResponse
    {
        $userId = $creator->createUser($data);

        return JsonResp::created(['id' => $userId]);
    }
}
