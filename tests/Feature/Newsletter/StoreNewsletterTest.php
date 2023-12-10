<?php

declare(strict_types=1);

namespace Tests\Feature\Newsletter;

use App\Jobs\IntegrateWithEsp;
use App\Modules\Esp\Services\EspApiKeyValidator;
use App\Modules\Esp\Services\EspFieldsCreator;
use App\Modules\Newsletter\Newsletter;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

class StoreNewsletterTest extends TestCase
{
    use SanctumTrait;

    public function testStoreNewsletter(): void
    {
        Queue::fake([IntegrateWithEsp::class]);
        $this->actAsCompleteUser();
        $this->loggedUser->team->newsletter()->delete();

        // mock
        $this->mock(EspApiKeyValidator::class)
            ->shouldReceive('apiKeyIsValid')
            ->once()
            ->andReturnTrue();
        $this->mock(EspFieldsCreator::class)
            ->shouldReceive('createFieldsIfNotExists')
            ->once();

        // given
        $requestData = [
            'name' => 'MKos Media Interactive Agency',
            'description' => 'MKos Media Interactive Agency',
            'landing_url' => 'https://google.com',
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
            'landing_url' => $requestData['landing_url'],
            'description' => $requestData['description'],
            'esp_name' => $requestData['esp_name'],
        ]);
        $apiKey = Newsletter::find($newsletterId)->esp_api_key;
        $this->assertEquals($requestData['esp_api_key'], $apiKey);
        Queue::assertPushed(IntegrateWithEsp::class);
    }

    public function testCannotStoreNewsletterIfTeamAlreadyHasOne(): void
    {
        $this->actAsCompleteUser();

        // given
        $requestData = [
            'name' => 'MKos Media Interactive Agency',
            'description' => 'MKos Media Interactive Agency',
            'landing_url' => 'https://google.com',
            'esp_name' => 'mailer_lite',
            'esp_api_key' => Str::random(),
        ];

        // when
        $response = $this->postJson('/api/newsletters', $requestData);

        // then
        $response->assertConflict();
        $this->assertEquals('Team already has a newsletter', $response->json('message'));
    }

    public function testCannotStoreNewsletterIfInvalidApiKey(): void
    {
        $this->actAsCompleteUser();
        $this->loggedUser->team->newsletter()->delete();

        // mock
        $this->mock(EspApiKeyValidator::class)
            ->shouldReceive('apiKeyIsValid')
            ->once()
            ->andReturnFalse();

        // given
        $requestData = [
            'name' => 'MKos Media Interactive Agency',
            'description' => 'MKos Media Interactive Agency',
            'landing_url' => 'https://google.com',
            'esp_name' => 'mailer_lite',
            'esp_api_key' => Str::random(),
        ];

        // when
        $response = $this->postJson('/api/newsletters', $requestData);

        // then
        $response->assertConflict();
        $this->assertEquals('Invalid ESP API key', $response->json('message'));
    }
}
