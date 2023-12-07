<?php

declare(strict_types=1);

namespace App\Modules\Team;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Team\Requests\CreateTeamRequest;
use App\Shared\Response\JsonResp;
use Illuminate\Http\JsonResponse;

class TeamController extends Controller
{
    public function store(CreateTeamRequest $data): JsonResponse
    {
        $user = User::findOrFail($data->owner_user_id);
        if ($user->ownedTeam()->exists()) {
            return JsonResp::badRequest('User already has a team');
        }

        $team = Team::create($data->toArray());
        $user->team()->associate($team);
        $user->save();

        return JsonResp::success(['id' => $team->id]);
    }
}
