<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\ActiveCampaign\Requests;

use App\Modules\Esp\Integration\WebhookEvent\WebhookEventRequestInterface;
use App\Modules\Subscriber\SubscriberStatus;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

final class ActiveCampaignWebhookEventRequest extends Data implements WebhookEventRequestInterface
{
    public function __construct(
        #[Required, In(['subscribe', 'unsubscribe', 'bounce'])]
        /** @var string{'subscribe', 'unsubscribe', 'bounce'} */
        public readonly string $type,

        #[Required]
        public readonly CovertKitWebhookEventRequestContact $contact,
    ) {
    }

    public function getId(): string
    {
        return (string)$this->contact->id;
    }

    public function getEmail(): string
    {
        return $this->contact->email;
    }

    public function getStatus(): SubscriberStatus
    {
        return match ($this->type) {
            'subscribe' => SubscriberStatus::Active,
            'unsubscribe' => SubscriberStatus::Unsubscribed,
            'bounce' => SubscriberStatus::Other,
        };
    }
}
