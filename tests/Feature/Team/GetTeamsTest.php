<?php

declare(strict_types=1);

namespace Tests\Feature\Team;

use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

class GetTeamsTest extends TestCase
{
    use SanctumTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actAsCompleteUser();
    }

    public function testGetTeam(): void
    {
        // when
        $response = $this->getJson('/api/team');

        // then
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json('data'));
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'name',
                'created_at',
                'updated_at',
            ],
        ]);
    }
}
