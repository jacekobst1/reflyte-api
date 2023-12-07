<?php

declare(strict_types=1);

namespace Tests\Feature\Team;

use App\Models\User;
use App\Modules\Team\Team;
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
        $user = User::factory()->create();

        // when
        $response = $this->postJson('/api/teams', [
            'name' => 'MKos Media Interactive Agency',
            'owner_user_id' => $user->id,
        ]);

        // then
        $response->assertSuccessful();
        $team = Team::whereName('MKos Media Interactive Agency')->with('users')->first();
        $this->assertEquals($user->id, $team->owner_user_id);
        $this->assertCount(1, $team->users);
        $this->assertEquals($user->id, $team->users->first()->id);
    }

    public function testCannotStoreTeamWithUserThatAlreadyHasATeam(): void
    {
        // given
        $user = User::factory()->create();
        $user->team()->associate(
            Team::factory()->for($user, 'owner')->create()
        );

        // when
        $response = $this->postJson('/api/teams', [
            'name' => 'MKos Media Interactive Agency',
            'owner_user_id' => $user->id,
        ]);

        // then
        $response->assertBadRequest();
        $this->assertEquals('User already has a team', $response->json('message'));
        $team = Team::whereName('MKos Media Interactive Agency')->with('users')->first();
        $this->assertNull($team);
    }
}
