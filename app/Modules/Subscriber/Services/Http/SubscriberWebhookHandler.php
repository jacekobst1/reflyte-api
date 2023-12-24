<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Services\Http;

use App\Modules\Esp\Integration\Clients\EspClientFactory;
use App\Modules\Esp\Integration\WebhookEvent\WebhookEventRequestFactory;
use App\Modules\Esp\Integration\WebhookEvent\WebhookEventRequestInterface;
use App\Modules\Newsletter\Newsletter;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberIsReferral;
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

        $subscriber = $this->updateOrCreateModel($newsletterId, $data);
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
        WebhookEventRequestInterface $data,
    ): Subscriber {
        $subscriber = Subscriber::whereNewsletterId($newsletterId)->whereEmail($data->getEmail())->first();

        if ($subscriber) {
            $subscriber->update([
                'esp_id' => $data->getId(),
                'status' => $data->getStatus()
            ]);
            return $subscriber;
        } else {
            return Subscriber::create([
                'newsletter_id' => $newsletterId,
                'esp_id' => $data->getId(),
                'email' => $data->getEmail(),
                'status' => $data->getStatus(),
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
