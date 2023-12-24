<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\WebhookEvent;

use App\Modules\Esp\EspName;
use App\Modules\Esp\Integration\Clients\ConvertKit\Requests\CovertKitWebhookEventRequest;
use App\Modules\Esp\Integration\Clients\MailerLite\Requests\MailerLiteWebhookEventRequest;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;

final class WebhookEventRequestFactory
{
    public function validateAndMake(NewsletterEspConfig $espConfig, array $data): WebhookEventRequestInterface
    {
        return match ($espConfig->espName) {
            EspName::MailerLite => MailerLiteWebhookEventRequest::validateAndCreate($data),
            EspName::ConvertKit => CovertKitWebhookEventRequest::validateAndCreate($data),
        };
    }
}
