<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Services\Http;

use App\Modules\Esp\Dto\EspSubscriberStatus;
use App\Modules\Esp\Integration\EspClientFactory;
use App\Modules\Subscriber\Requests\MailerLiteWebhookEventRequest;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberIsRef;
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
        $subscriber = Subscriber::whereNewsletterId($newsletterId)->whereEmail($email)->first();

        if ($subscriber) {
            $subscriber->update(['status' => $status]);
            return $subscriber;
        } else {
            return Subscriber::create([
                'newsletter_id' => $newsletterId,
                'email' => $email,
                'status' => $status,
                'is_ref' => SubscriberIsRef::No,
            ]);
        }
    }

    private function updateEspSubscriberFields(string $id, Subscriber $subscriber): void
    {
        $espConfig = $subscriber->newsletter->getEspConfig();
        $espClient = $this->espClientFactory->make($espConfig);

        $espClient->updateSubscriberFields($id, RltFields::getSubscriberFields($subscriber));
    }
}
