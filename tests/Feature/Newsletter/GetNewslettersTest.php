<?php

declare(strict_types=1);

namespace Feature\Newsletter;

use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

class GetNewslettersTest extends TestCase
{
    use SanctumTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actAsCompleteUser();
    }

    public function testGetNewsletters(): void
    {
        // when
        $response = $this->getJson('/api/newsletter');

        // then
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json('data'));
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'name',
                'description',
                'created_at',
                'updated_at',
            ],
        ]);
    }
}
