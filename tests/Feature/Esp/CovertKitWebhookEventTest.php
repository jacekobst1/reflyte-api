<?php

declare(strict_types=1);

namespace Tests\Feature\Esp;

use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Esp\Integration\Clients\ConvertKit\ConvertKitClient;
use App\Modules\Esp\Integration\Clients\EspClientFactory;
use App\Modules\Newsletter\Newsletter;
use App\Modules\Subscriber\SubscriberIsReferral;
use App\Modules\Subscriber\SubscriberStatus;
use Illuminate\Support\Str;
use Tests\TestCase;

final class CovertKitWebhookEventTest extends TestCase
{
    public function testCorrectStructure(): void
    {
        // given
        $newsletter = Newsletter::factory()->convertKit()->create();
        $data = [
            'subscriber' => [
                'id' => rand(),
                'email_address' => Str::random() . '@test.com',
                'state' => SubscriberStatus::Active->value,
            ]
        ];

        // mock
        $this->mockEspClient();

        // when
        $response = $this->postJson("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertOk();
        $this->assertDatabaseHas('subscribers', [
            'newsletter_id' => $newsletter->id,
            'esp_id' => $data['subscriber']['id'],
            'email' => $data['subscriber']['email_address'],
            'status' => SubscriberStatus::Active,
            'is_referral' => SubscriberIsReferral::No,
        ]);
    }

    public function testEmptyFields(): void
    {
        // given
        $newsletter = Newsletter::factory()->convertKit()->create();
        $data = [
            'subscriber' => [
                'id' => null,
                'email_address' => null,
                'state' => null,
            ]
        ];

        // when
        $response = $this->postJson("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertUnprocessable();
    }

    public function testInvalidStructure(): void
    {
        // given
        $newsletter = Newsletter::factory()->convertKit()->create();
        $data = [
            'id' => Str::random(),
            'email_address' => Str::random() . '@test.com',
            'state' => SubscriberStatus::Active->value,
        ];

        // when
        $response = $this->postJson("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertUnprocessable();
    }

    private function mockEspClient(): void
    {
        $espSubscriber = new EspSubscriberDto(
            id: Str::random(),
            email: Str::random() . '@test.com',
            status: SubscriberStatus::Active,
        );

        $convertKitEspClientMock = $this->mock(ConvertKitClient::class);
        $convertKitEspClientMock->shouldReceive('getSubscriber')
            ->once()
            ->andReturn($espSubscriber);
        $convertKitEspClientMock->shouldReceive('updateSubscriberFields')
            ->once()
            ->andReturn(true);

        $espClientFactoryMock = $this->mock(EspClientFactory::class);
        $espClientFactoryMock->shouldReceive('make')
            ->twice()
            ->andReturn($convertKitEspClientMock);
    }
}
