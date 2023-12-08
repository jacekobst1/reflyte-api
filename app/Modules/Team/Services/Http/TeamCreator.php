<?php

declare(strict_types=1);

namespace App\Modules\Team\Services\Http;

use App\Exceptions\BadRequestException;
use App\Modules\Team\Requests\CreateTeamRequest;
use App\Modules\Team\Team;
use App\Modules\User\User;
use Illuminate\Support\Facades\Auth;
use Throwable;

final class TeamCreator
{
    /**
     * @throws BadRequestException
     * @throws Throwable
     */
    public function createTeam(CreateTeamRequest $data): Team
    {
        $user = Auth::user();

        $this->checkIfUserHasNoTeam();

        $team = $this->storeTeam($data);
        $this->associateTeamToUser($team, $user);

        return $team;
    }

    /**
     * @throws BadRequestException
     */
    private function checkIfUserHasNoTeam(): void
    {
        $user = Auth::user();

        if ($user->team()->exists()) {
            throw new BadRequestException('User already has a team');
        }
    }

    /**
     * @throws Throwable
     */
    private function storeTeam(CreateTeamRequest $data): Team
    {
        $team = new Team();

        $team->fill($data->toArray());
        $team->owner_user_id = Auth::id();

        $team->saveOrFail();

        return $team;
    }

    /**
     * @throws Throwable
     */
    private function associateTeamToUser(Team $team, User $user): void
    {
        $user->team()->associate($team);
        $user->saveOrFail();
    }
}
