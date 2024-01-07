<?php

declare(strict_types=1);

namespace App\Modules\Team;

use App\Exceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Modules\Team\Requests\CreateTeamRequest;
use App\Modules\Team\Resources\TeamResource;
use App\Modules\Team\Services\Http\TeamCreator;
use App\Shared\Response\JsonResp;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;

class TeamController extends Controller
{
    public function getUserTeam(): JsonResponse
    {
        $team = Auth::user()->team;

        return JsonResp::success(
            $team ? new TeamResource($team) : null
        );
    }

    /**
     * @throws BadRequestException
     * @throws Throwable
     */
    public function postTeam(CreateTeamRequest $data, TeamCreator $creator): JsonResponse
    {
        $team = $creator->createTeam($data);

        return JsonResp::success(['id' => $team->id]);
    }
}
