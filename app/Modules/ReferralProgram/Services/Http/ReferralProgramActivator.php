<?php

declare(strict_types=1);

namespace App\Modules\ReferralProgram\Services\Http;

use App\Modules\ReferralProgram\ReferralProgram;

final class ReferralProgramActivator
{
    public function activateReferralProgram(ReferralProgram $referralProgram): void
    {
        $referralProgram->active = true;
        $referralProgram->save();
    }
}
