<?php

declare(strict_types=1);

namespace Tests\Feature\Subscriber;

use App\Modules\Subscriber\Subscriber;
use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

class GetSubscribersTest extends TestCase
{
    use SanctumTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actAsCompleteUser();
    }

    public function testGetSubscribers(): void
    {
        // given
        $newsletter = $this->loggedUser->getNewsletter();
        Subscriber::factory()->for($newsletter)->count(3)->create();

        // when
        $response = $this->getJson('/api/subscribers');

        // then
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json('data'));
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'email',
                    'status',
                    'ref_code',
                    'ref_link',
                    'created_at',
                    'updated_at',
                ]
            ],
            'links',
            'meta',
        ]);
    }
}
