<?php

declare(strict_types=1);

namespace Tests\Feature\Reward;

use App\Modules\Reward\Reward;
use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

final class GetReferralProgramRewardsTest extends TestCase
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
        Reward::factory()->for($referralProgram, 'rewardable')->create();

        // when
        $response = $this->get("/api/referral-programs/$referralProgram->id/rewards");

        // then
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }
}
