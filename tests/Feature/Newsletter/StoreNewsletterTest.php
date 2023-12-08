<?php

declare(strict_types=1);

namespace Tests\Feature\Newsletter;

use Illuminate\Support\Str;
use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

class StoreNewsletterTest extends TestCase
{
    use SanctumTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actAsUserWithTeam();
    }

    public function testStoreNewsletter(): void
    {
        // given
        $requestData = [
            'name' => 'MKos Media Interactive Agency',
            'description' => 'MKos Media Interactive Agency',
            'esp_name' => 'mailer_lite',
            'esp_api_key' => Str::random(),
        ];

        // when
        $response = $this->postJson('/api/newsletters', $requestData);

        // then
        $response->assertSuccessful();
        $newsletterId = $response->json('data.id');
        $this->assertDatabaseHas('newsletters', [
            'id' => $newsletterId,
            'name' => $requestData['name'],
            'description' => $requestData['description'],
            'esp_name' => $requestData['esp_name'],
            'esp_api_key' => $requestData['esp_api_key'],
        ]);
    }
}
