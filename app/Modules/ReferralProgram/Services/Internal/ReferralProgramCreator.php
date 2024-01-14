<?php

declare(strict_types=1);

namespace App\Modules\ReferralProgram\Services\Internal;

use App\Exceptions\ConflictException;
use App\Modules\Newsletter\Newsletter;
use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Team\Team;

class ReferralProgramCreator
{
    /**
     * @throws ConflictException
     */
    public function createReferralProgram(Newsletter $newsletter): ReferralProgram
    {
        $team = $newsletter->team;

        $this->checkIfTeamHasNoReferralProgram($team);

        /** @var ReferralProgram */
        return $newsletter->referralProgram()->create();
    }

    /**
     * @throws ConflictException
     */
    private function checkIfTeamHasNoReferralProgram(Team $team): void
    {
        if ($team->getReferralProgram()) {
            throw new ConflictException('Team already has a referral program');
        }
    }
}
