<?php

declare(strict_types=1);

namespace Tests\Feature\Newsletter;

use App\Jobs\IntegrateWithEsp\IntegrateWithEspJob;
use App\Modules\Esp\EspName;
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
        Queue::fake([IntegrateWithEspJob::class]);
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
        $requestData = $this->getRequestData();

        // when
        $response = $this->postJson('/api/newsletters', $requestData);

        // then
        $response->assertSuccessful();
        $newsletterId = $response->json('data.id');
        $newsletter = Newsletter::find($newsletterId);

        $this->assertEquals($requestData['name'], $newsletter->name);
        $this->assertEquals($requestData['landing_url'], $newsletter->landing_url);
        $this->assertEquals($requestData['description'], $newsletter->description);
        $this->assertEquals($requestData['esp_name'], $newsletter->esp_name->value);
        $this->assertEquals($requestData['esp_api_key'], $newsletter->esp_api_key);

        $this->assertModelExists($newsletter->referralProgram);
        Queue::assertPushed(IntegrateWithEspJob::class);
    }

    public function testStoreActiveCampaignNewsletter(): void
    {
        $apiUrl = 'https://proton12345.api-us1.com';
        Queue::fake([IntegrateWithEspJob::class]);
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
        $requestData = $this->getRequestData();
        $requestData['esp_api_url'] = $apiUrl;

        // when
        $response = $this->postJson('/api/newsletters', $requestData);

        // then
        $response->assertSuccessful();
        $newsletterId = $response->json('data.id');
        $newsletter = Newsletter::find($newsletterId);

        $this->assertEquals($requestData['name'], $newsletter->name);
        $this->assertEquals($requestData['landing_url'], $newsletter->landing_url);
        $this->assertEquals($requestData['description'], $newsletter->description);
        $this->assertEquals($requestData['esp_name'], $newsletter->esp_name->value);
        $this->assertEquals($requestData['esp_api_key'], $newsletter->esp_api_key);
        $this->assertEquals($requestData['esp_api_url'], $newsletter->esp_api_url);

        $this->assertModelExists($newsletter->referralProgram);
        Queue::assertPushed(IntegrateWithEspJob::class);
    }

    public function testStoreActiveCampaignNewsletterWithoutApiUrl(): void
    {
        Queue::fake([IntegrateWithEspJob::class]);
        $this->actAsCompleteUser();
        $this->loggedUser->team->newsletter()->delete();

        // given
        $requestData = $this->getRequestData(EspName::ActiveCampaign);

        // when
        $response = $this->postJson('/api/newsletters', $requestData);

        // then
        $response->assertUnprocessable();
        Queue::assertNotPushed(IntegrateWithEspJob::class);
    }

    public function testCannotStoreNewsletterIfTeamAlreadyHasOne(): void
    {
        $this->actAsCompleteUser();

        // when
        $response = $this->postJson('/api/newsletters', $this->getRequestData());

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

        // when
        $response = $this->postJson('/api/newsletters', $this->getRequestData());

        // then
        $response->assertConflict();
        $this->assertEquals('Invalid ESP API key', $response->json('message'));
    }

    private function getRequestData(?EspName $espName = EspName::MailerLite): array
    {
        return [
            'name' => 'MKos Media Interactive Agency',
            'description' => 'MKos Media Interactive Agency',
            'landing_url' => 'https://google.com',
            'esp_name' => $espName->value,
            'esp_api_key' => Str::random(),
        ];
    }
}
