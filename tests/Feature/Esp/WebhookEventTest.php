<?php

declare(strict_types=1);

namespace Feature\Esp;

use App\Modules\Esp\Dto\EspSubscriberStatus;
use App\Modules\Esp\Integration\EspClientFactory;
use App\Modules\Esp\Integration\MailerLite\MailerLiteClient;
use App\Modules\Newsletter\Newsletter;
use App\Modules\Subscriber\SubscriberStatus;
use Illuminate\Support\Str;
use Tests\TestCase;

final class WebhookEventTest extends TestCase
{
    public function testSuccess(): void
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
        ]);
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
