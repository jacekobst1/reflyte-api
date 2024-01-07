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
        /** @var User $user */
        $user = $request->user();
        $user->load('team.newsletter');

        return $user;
    }

    /**
     * @return Collection<array-key, User>
     */
    public function getAll(): Collection
    {
        return User::all();
    }
}
