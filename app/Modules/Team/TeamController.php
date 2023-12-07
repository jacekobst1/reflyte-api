<?php

declare(strict_types=1);

namespace App\Modules\Team;

use App\Exceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Modules\Team\Requests\CreateTeamRequest;
use App\Modules\Team\Services\TeamCreator;
use App\Shared\Response\JsonResp;
use Illuminate\Http\JsonResponse;
use Throwable;

class TeamController extends Controller
{
    /**
     * @throws BadRequestException
     * @throws Throwable
     */
    public function store(CreateTeamRequest $data, TeamCreator $creator): JsonResponse
    {
        $team = $creator->createTeam($data);

        return JsonResp::success(['id' => $team->id]);
    }
}
