<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Services\Http;

use App\Modules\Esp\Integration\EspClientFactory;
use App\Modules\Esp\Integration\WebhookEvent\WebhookEventRequestFactory;
use App\Modules\Esp\Integration\WebhookEvent\WebhookEventRequestInterface;
use App\Modules\Newsletter\Newsletter;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberIsReferral;
use App\Modules\Subscriber\SubscriberStatus;
use App\Shared\RltFields;
use Ramsey\Uuid\UuidInterface;

final readonly class SubscriberWebhookHandler
{
    public function __construct(
        private WebhookEventRequestFactory $webhookEventRequestFactory,
        private EspClientFactory $espClientFactory
    ) {
    }

    public function updateOrCreate(
        UuidInterface $newsletterId,
        array $rawData
    ): bool {
        $data = $this->validateAndMakeDataObject($newsletterId, $rawData);

        $subscriber = $this->updateOrCreateModel($newsletterId, $data->getEmail(), $data->getStatus());
        $this->updateEspSubscriberFields($data->getId(), $subscriber);

        // TODO reward logic

        return true;
    }

    private function validateAndMakeDataObject(
        UuidInterface $newsletterId,
        array $rawData
    ): WebhookEventRequestInterface {
        $newsletter = Newsletter::findOrFail($newsletterId);

        return $this->webhookEventRequestFactory->validateAndMake($newsletter->getEspConfig(), $rawData);
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
                'is_referral' => SubscriberIsReferral::No,
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
