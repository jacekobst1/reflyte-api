<?php

declare(strict_types=1);

namespace Tests\Feature\Esp;

use App\Modules\Esp\Integration\Clients\ConvertKit\ConvertKitClient;
use App\Modules\Esp\Integration\Clients\EspClientFactory;
use App\Modules\Esp\Integration\Clients\MailerLite\MailerLiteClient;
use App\Modules\Newsletter\Newsletter;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberIsReferral;
use App\Modules\Subscriber\SubscriberStatus;
use Illuminate\Support\Str;
use Tests\TestCase;

final class WebhookEventTest extends TestCase
{
    public function testWhenSubscriberNotExistsFromMailerLite(): void
    {
        // given
        $newsletter = Newsletter::factory()->create();
        $data = [
            'id' => Str::random(),
            'email' => Str::random() . '@test.com',
            'status' => SubscriberStatus::Active->value,
        ];

        // mock
        $mailerLiteEspClientMock = $this->mock(MailerLiteClient::class);
        $mailerLiteEspClientMock->shouldReceive('updateSubscriberFields')
            ->once()
            ->andReturn(true);
        $espClientFactoryMock = $this->mock(EspClientFactory::class);
        $espClientFactoryMock->shouldReceive('make')
            ->once()
            ->andReturn($mailerLiteEspClientMock);

        // when
        $response = $this->postJson("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertOk();
        $this->assertDatabaseHas('subscribers', [
            'newsletter_id' => $newsletter->id,
            'esp_id' => $data['id'],
            'email' => $data['email'],
            'status' => SubscriberStatus::Active,
            'is_referral' => SubscriberIsReferral::No,
        ]);
    }

    public function testWhenSubscriberAlreadyExistsFromMailerLite()
    {
        $email = Str::random() . '@test.com';
        $newsletter = Newsletter::factory()->create();
        $subscriber = Subscriber::factory()->for($newsletter)->create([
            'email' => $email,
            'status' => SubscriberStatus::Received,
            'is_referral' => SubscriberIsReferral::Yes,
        ]);
        $data = [
            'id' => Str::random(),
            'email' => $email,
            'status' => SubscriberStatus::Active->value,
        ];

        // mock
        $mailerLiteEspClientMock = $this->mock(MailerLiteClient::class);
        $mailerLiteEspClientMock->shouldReceive('updateSubscriberFields')
            ->once()
            ->andReturn(true);
        $espClientFactoryMock = $this->mock(EspClientFactory::class);
        $espClientFactoryMock->shouldReceive('make')
            ->once()
            ->andReturn($mailerLiteEspClientMock);

        // when
        $response = $this->postJson("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertOk();
        $this->assertEquals(SubscriberStatus::Active, $subscriber->refresh()->status);
    }

    public function testWhenSubscriberNotExistsFromConvertKit(): void
    {
        // given
        $newsletter = Newsletter::factory()->convertKit()->create();
        $data = [
            'id' => Str::random(),
            'email' => Str::random() . '@test.com',
            'state' => SubscriberStatus::Active->value,
        ];

        // mock
        $convertKitEspClientMock = $this->mock(ConvertKitClient::class);
        $convertKitEspClientMock->shouldReceive('updateSubscriberFields')
            ->once()
            ->andReturn(true);
        $espClientFactoryMock = $this->mock(EspClientFactory::class);
        $espClientFactoryMock->shouldReceive('make')
            ->once()
            ->andReturn($convertKitEspClientMock);

        // when
        $response = $this->postJson("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertOk();
        $this->assertDatabaseHas('subscribers', [
            'newsletter_id' => $newsletter->id,
            'esp_id' => $data['id'],
            'email' => $data['email'],
            'status' => SubscriberStatus::Active,
            'is_referral' => SubscriberIsReferral::No,
        ]);
    }

    public function testWrongUuid(): void
    {
        // given
        $data = [
            'id' => Str::random(),
            'email' => Str::random() . '@test.com',
            'status' => SubscriberStatus::Active->value,
        ];

        // when
        $response = $this->postJson('/api/esp/webhook/abc', $data);

        // then
        $response->assertBadRequest();
        $this->assertEquals('Invalid uuid', $response->json('message'));
    }
}
