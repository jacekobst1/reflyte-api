<?php

declare(strict_types=1);

namespace Tests\Feature\Reward;

use App\Modules\ReferralProgram\ReferralProgram;
use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

final class StoreProgramRewardTest extends TestCase
{
    use SanctumTrait;

    public function testStore(): void
    {
        $this->actAsUser();
        
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
}
