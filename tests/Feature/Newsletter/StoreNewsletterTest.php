<?php

declare(strict_types=1);

namespace Tests\Feature\Newsletter;

use App\Modules\Team\Team;
use App\Modules\Team\TeamRelations;
use Illuminate\Support\Facades\Auth;
use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

class StoreNewsletterTest extends TestCase
{
    use SanctumTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actAsUser();
    }

    public function testStoreNewsletter(): void
    {
        // given
        $requestData = [
            'name' => 'MKos Media Interactive Agency',
            'description' => 'MKos Media Interactive Agency',
        ];

        // when
        $response = $this->postJson('/api/newsletters', $requestData);

        // then
        $response->assertSuccessful();
        $team = Team::whereName('MKos Media Interactive Agency')->with(TeamRelations::USERS)->first();
        $this->assertEquals(Auth::id(), $team->owner_user_id);
        $this->assertCount(1, $team->users);
        $this->assertEquals(Auth::id(), $team->users->first()->id);
    }
}
