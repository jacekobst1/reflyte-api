<?php

declare(strict_types=1);

namespace Tests\Feature\Reward;

use App\Modules\ReferralProgram\ReferralProgram;
use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

final class StoreProgramRewardTest extends TestCase
{
    use SanctumTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actAsCompleteUser();
    }

    public function testStore(): void
    {
        // given
        $referralProgram = ReferralProgram::factory()->create();
        $data = [
            'name' => 'Reward name',
            'description' => 'Reward description',
            'required_points' => 15,
        ];

        // when
        $response = $this->postJson("/api/referral-programs/$referralProgram->id/rewards", $data);

        // then
        $response->assertCreated();
        $response->assertJsonStructure(['data' => ['id']]);
        $this->assertEquals(1, $referralProgram->rewards()->count());
    }

    public function testCannotStoreRewardWithTheSameRequiredPoints(): void
    {
        // given
        $referralProgram = ReferralProgram::factory()
            ->hasRewards(1, ['required_points' => 8])
            ->create();

        $data = [
            'name' => 'Reward name',
            'description' => 'Reward description',
            'required_points' => 8,
        ];

        // when
        $response = $this->postJson("/api/referral-programs/$referralProgram->id/rewards", $data);

        // then
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['required_points' => 'The required points has already been taken.']);
    }
}
