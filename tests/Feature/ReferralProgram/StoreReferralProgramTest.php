<?php

declare(strict_types=1);

namespace Tests\Feature\ReferralProgram;

use Illuminate\Support\Facades\Auth;
use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

class StoreReferralProgramTest extends TestCase
{
    use SanctumTrait;

    public function testReferralProgramCreated(): void
    {
        $this->actAsCompleteUser();
        $this->loggedUser->getReferralProgram()->delete();
        Auth::user()->refresh();

        // when
        $response = $this->postJson('/api/referral-programs');

        // then
        $response->assertCreated();
    }

    public function testReferralProgramCannotBeCreatedIfAlreadyExists(): void
    {
        $this->actAsCompleteUser();

        // when
        $response = $this->postJson('/api/referral-programs');

        // then
        $response->assertConflict();
        $this->assertSame('Team already has a referral program', $response->json('message'));
    }
}
