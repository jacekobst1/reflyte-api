<?php

declare(strict_types=1);

namespace App\Modules\Team\Services;

use App\Exceptions\BadRequestException;
use App\Models\User;
use App\Modules\Team\Requests\CreateTeamRequest;
use App\Modules\Team\Team;

final class TeamCreator
{
    /**
     * @throws BadRequestException
     */
    public function createTeam(CreateTeamRequest $data): Team
    {
        $user = $this->getUser($data);
        $team = Team::create($data->toArray());
        $this->associateTeamToUser($team, $user);

        return $team;
    }

    /**
     * @throws BadRequestException
     */
    private function getUser(CreateTeamRequest $data): User
    {
        $user = User::findOrFail($data->owner_user_id);
        if ($user->ownedTeam()->exists()) {
            throw new BadRequestException('User already has a team');
        }

        return $user;
    }

    private function associateTeamToUser(Team $team, User $user): void
    {
        $user->team()->associate($team);
        $user->save();
    }
}
