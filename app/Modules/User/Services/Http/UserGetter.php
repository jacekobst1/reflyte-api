<?php

declare(strict_types=1);

namespace App\Modules\User\Services\Http;

use App\Modules\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

final class UserGetter
{
    public function getLoggedUser(Request $request): User
    {
        return $request->user();
    }

    public function getAll(): Collection
    {
        return User::all();
    }
}
