<?php

declare(strict_types=1);

namespace Tests\Feature\Esp;

use App\Modules\Esp\Dto\EspSubscriberStatus;
use App\Modules\Esp\Integration\EspClientFactory;
use App\Modules\Esp\Integration\MailerLite\MailerLiteClient;
use App\Modules\Newsletter\Newsletter;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberIsRef;
use App\Modules\Subscriber\SubscriberStatus;
use Illuminate\Support\Str;
use Tests\TestCase;

final class WebhookEventTest extends TestCase
{
    public function testWhenSubscriberNotExists(): void
    {
        // given
        $newsletter = Newsletter::factory()->create();
        $data = [
            'id' => '123',
            'email' => Str::random() . '@test.com',
            'status' => EspSubscriberStatus::Active->value,
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
        $response = $this->post("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertOk();
        $this->assertDatabaseHas('subscribers', [
            'newsletter_id' => $newsletter->id,
            'email' => $data['email'],
            'status' => SubscriberStatus::Active,
            'is_ref' => SubscriberIsRef::No,
        ]);
    }

    public function testWhenSubscriberAlreadyExists()
    {
        $email = Str::random() . '@test.com';
        $newsletter = Newsletter::factory()->create();
        $subscriber = Subscriber::factory()->for($newsletter)->create([
            'email' => $email,
            'status' => SubscriberStatus::Received,
            'is_ref' => SubscriberIsRef::Yes,
        ]);
        $data = [
            'id' => '123',
            'email' => $email,
            'status' => EspSubscriberStatus::Active->value,
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
        $response = $this->post("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertOk();
        $this->assertEquals(SubscriberStatus::Active, $subscriber->refresh()->status);
    }

    public function testWrongUuid(): void
    {
        // given
        $data = [
            'id' => '123',
            'email' => Str::random() . '@test.com',
            'status' => EspSubscriberStatus::Active->value,
        ];

        // when
        $response = $this->post('/api/esp/webhook/abc', $data);

        // then
        $response->assertBadRequest();
        $this->assertEquals('Invalid uuid', $response->json('message'));
    }
}
