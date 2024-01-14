<?php

declare(strict_types=1);

namespace Tests\Feature\ReferralProgram;

use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

class GetReferralProgramTest extends TestCase
{
    use SanctumTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actAsCompleteUser();
    }

    public function testGetReferralProgram(): void
    {
        // when
        $response = $this->getJson('/api/referral-program');

        // then
        $response->assertSuccessful();
        $this->assertNotEmpty($response->json('data'));
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'active',
                'created_at',
                'updated_at',
            ],
        ]);
    }
}
