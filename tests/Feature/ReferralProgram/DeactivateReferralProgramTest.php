<?php

declare(strict_types=1);

namespace Tests\Feature\ReferralProgram;

use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

class DeactivateReferralProgramTest extends TestCase
{
    use SanctumTrait;

    public function testActivateReferralProgram(): void
    {
        $this->actAsCompleteUser();
        $referralProgram = $this->loggedUser->getReferralProgram();
        $referralProgram->active = true;
        $referralProgram->save();

        // when
        $response = $this->postJson("/api/referral-programs/$referralProgram->id/deactivate");

        // then
        $referralProgram->refresh();

        $response->assertSuccessful();
        $this->assertFalse($referralProgram->active);
    }
}
