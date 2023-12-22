<?php

declare(strict_types=1);

namespace Tests\Feature\Reward;

use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Reward\Reward;
use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

final class DeleteRewardTest extends TestCase
{
    use SanctumTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actAsCompleteUser();
    }

    public function testSuccess(): void
    {
        // given
        $referralProgram = $this->loggedUser->getReferralProgram();
        $reward = Reward::factory()->for($referralProgram, 'rewardable')->create();

        // when
        $response = $this->delete("/api/rewards/$reward->id");

        // then
        $response->assertSuccessful();
        $this->assertDatabaseMissing('rewards', ['id' => $reward->id]);
    }

    public function testCannotViewAnotherTeamReward(): void
    {
        // given
        $referralProgram = ReferralProgram::factory()->create();
        $reward = Reward::factory()->for($referralProgram, 'rewardable')->create();

        // when
        $response = $this->delete("/api/rewards/$reward->id");

        // then
        $response->assertForbidden();
    }
}
