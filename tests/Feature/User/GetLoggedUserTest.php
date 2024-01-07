<?php

declare(strict_types=1);

namespace Feature\User;

use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

class GetLoggedUserTest extends TestCase
{
    use SanctumTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testGetLoggedUser(): void
    {
        $this->actAsUser();

        // when
        $response = $this->getJson('/api/logged-user');

        // then
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json('data'));
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'name',
                'email',
                'hasTeam',
                'hasNewsletter',
            ],
        ]);
    }

    public function testGetLoggedUserWithoutTeamAndNewsletter(): void
    {
        $this->actAsUser();

        // when
        $response = $this->getJson('/api/logged-user');

        // then
        $data = $response->json('data');

        $this->assertFalse($data['hasTeam']);
        $this->assertFalse($data['hasNewsletter']);
    }


    public function testGetLoggedUserWithTeamAndNewsletter(): void
    {
        $this->actAsCompleteUser();

        // when
        $response = $this->getJson('/api/logged-user');

        // then
        $data = $response->json('data');

        $this->assertTrue($data['hasTeam']);
        $this->assertTrue($data['hasNewsletter']);
    }
}
