<?php

declare(strict_types=1);

namespace Feature\Esp;

use App\Modules\Esp\Dto\EspSubscriberStatus;
use Illuminate\Support\Str;
use Tests\TestCase;

final class MailerLiteTest extends TestCase
{
    public function testWebhookEvent(): void
    {
        // given
        $data = [
            'id' => '123',
            'email' => Str::random() . '@test.com',
            'status' => EspSubscriberStatus::Active->value,
        ];

        // when
        $response = $this->post('/api/esp/webhook/mailer-lite', $data);

        // then
        $response->assertOk();
    }
}
