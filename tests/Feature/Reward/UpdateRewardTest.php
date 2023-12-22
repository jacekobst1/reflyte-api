<?php

declare(strict_types=1);

namespace Tests\Feature\Reward;

use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Reward\Reward;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

final class UpdateRewardTest extends TestCase
{
    use SanctumTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actAsCompleteUser();
    }

    public function testUpdate(): void
    {
        // given
        $referralProgram = $this->loggedUser->team->newsletter->referralProgram;
        $reward = Reward::factory()->for($referralProgram, 'rewardable')->create();
        $data = [
            'name' => 'New name' . Str::random(5),
            'description' => $reward->description,
            'required_points' => $reward->required_points,
        ];

        // when
        $response = $this->putJson("/api/rewards/$reward->id", $data);

        // then
        $response->assertSuccessful();
        $response->assertJsonStructure(['data' => ['id']]);
        $this->assertEquals($data['name'], $reward->refresh()->name);
    }

    public function testCannotUpdateAnotherTeamReward(): void
    {
        // given
        $reward = Reward::factory()->for(ReferralProgram::factory(), 'rewardable')->create();


        $data = [
            'name' => 'New name' . Str::random(5),
            'description' => $reward->description,
            'required_points' => $reward->required_points,
        ];

        // when
        $response = $this->put("/api/rewards/$reward->id", $data);

        // then
        $response->assertForbidden();
        $response->assertExactJson([
            'status' => JsonResponse::HTTP_FORBIDDEN,
            'message' => 'Unauthorized',
        ]);
    }
}
