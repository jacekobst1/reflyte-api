<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\ConvertKit\Requests;

use App\Modules\Esp\Integration\WebhookEvent\WebhookEventRequestInterface;
use App\Modules\Subscriber\SubscriberStatus;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

final class CovertKitWebhookEventRequest extends Data implements WebhookEventRequestInterface
{
    public function __construct(
        #[Required]
        public readonly CovertKitWebhookEventRequestSubscriber $subscriber,
    ) {
    }

    public function getId(): string
    {
        return $this->subscriber->id;
    }

    public function getEmail(): string
    {
        return $this->subscriber->email_address;
    }

    public function getStatus(): SubscriberStatus
    {
        return $this->subscriber->getStatusEnum();
    }
}
