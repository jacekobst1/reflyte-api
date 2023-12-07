<?php

declare(strict_types=1);

namespace Tests\Feature\Team;

use App\Modules\Team\Team;
use App\Modules\Team\TeamRelations;
use Illuminate\Support\Facades\Auth;
use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

class StoreTeamTest extends TestCase
{
    use SanctumTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actAsUser();
    }

    public function testStoreTeam(): void
    {
        // given
        $requestData = [
            'name' => 'MKos Media Interactive Agency',
        ];

        // when
        $response = $this->postJson('/api/teams', $requestData);

        // then
        $response->assertSuccessful();
        $team = Team::whereName('MKos Media Interactive Agency')->with(TeamRelations::USERS)->first();
        $this->assertEquals(Auth::id(), $team->owner_user_id);
        $this->assertCount(1, $team->users);
        $this->assertEquals(Auth::id(), $team->users->first()->id);
    }

    public function testCannotStoreTeamWithUserThatAlreadyHasATeam(): void
    {
        // given
        $user = Auth::user();
        $user->team()->associate(
            Team::factory()->for($user, 'owner')->create()
        );
        $requestData = [
            'name' => 'MKos Media Interactive Agency',
        ];

        // when
        $response = $this->postJson('/api/teams', $requestData);

        // then
        $response->assertBadRequest();
        $this->assertEquals('User already has a team', $response->json('message'));
        $team = Team::whereName('MKos Media Interactive Agency')->with(TeamRelations::USERS)->first();
        $this->assertNull($team);
    }
}
