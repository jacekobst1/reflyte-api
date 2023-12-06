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

        $this->actAsUser();
    }

    public function testGetLoggedUser(): void
    {
        // when
        $response = $this->getJson('/api/logged-user');

        // then
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json('data'));
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
        ]);
    }
}
