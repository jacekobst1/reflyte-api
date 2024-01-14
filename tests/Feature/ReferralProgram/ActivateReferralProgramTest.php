<?php

declare(strict_types=1);

namespace Tests\Feature\ReferralProgram;

use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

class ActivateReferralProgramTest extends TestCase
{
    use SanctumTrait;

    public function testActivateReferralProgram(): void
    {
        $this->actAsCompleteUser();
        $referralProgram = $this->loggedUser->getReferralProgram();

        // when
        $response = $this->postJson("/api/referral-programs/$referralProgram->id/activate");

        // then
        $response->assertSuccessful();
    }
}
