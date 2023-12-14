<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Services\Http;

use App\Modules\Esp\Dto\EspSubscriberStatus;
use App\Modules\Esp\Integration\EspClientFactory;
use App\Modules\Subscriber\Requests\MailerLiteWebhookEventRequest;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberStatus;
use App\Shared\RltFields;
use Ramsey\Uuid\UuidInterface;

final class SubscriberWebhookHandler
{
    public function __construct(private readonly EspClientFactory $espClientFactory)
    {
    }

    public function updateOrCreate(
        UuidInterface $newsletterId,
        MailerLiteWebhookEventRequest $data
    ): bool {
        $status = $this->getStatusEnum($data->status);
        $subscriber = $this->updateOrCreateModel($newsletterId, $data->email, $status);
        $this->updateEspSubscriberFields($data->id, $subscriber);

        // TODO reward logic

        return true;
    }

    private function getStatusEnum(string $status): SubscriberStatus
    {
        return $status === EspSubscriberStatus::Active->value
            ? SubscriberStatus::Active
            : SubscriberStatus::Inactive;
    }

    private function updateOrCreateModel(
        UuidInterface $newsletterId,
        string $email,
        SubscriberStatus $status
    ): Subscriber {
        return Subscriber::updateOrCreate(
            [
                'newsletter_id' => $newsletterId,
                'email' => $email
            ],
            ['status' => $status]
        );
    }

    private function updateEspSubscriberFields(string $id, Subscriber $subscriber): void
    {
        $espConfig = $subscriber->newsletter->getEspConfig();
        $client = $this->espClientFactory->make($espConfig->espName, $espConfig->espApiKey);

        $client->updateSubscriber($id, [
            'fields' => RltFields::getSubscriberFields($subscriber),
        ]);
    }
}
