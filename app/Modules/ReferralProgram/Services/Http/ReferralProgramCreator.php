<?php

declare(strict_types=1);

namespace App\Modules\ReferralProgram\Services\Http;

use App\Exceptions\ConflictException;
use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Team\Team;
use Illuminate\Support\Facades\Auth;

class ReferralProgramCreator
{
    /**
     * @throws ConflictException
     */
    public function createReferralProgram(): ReferralProgram
    {
        $team = Auth::user()->team;
        
        $this->checkIfTeamHasNoReferralProgram($team);

        /** @var ReferralProgram */
        return $team->newsletter->referralProgram()->create();
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
