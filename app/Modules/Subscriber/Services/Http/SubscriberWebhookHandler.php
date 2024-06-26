<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Services\Http;

use App\Modules\Esp\Integration\Clients\EspClientFactory;
use App\Modules\Esp\Integration\WebhookEvent\WebhookEventRequestFactory;
use App\Modules\Esp\Integration\WebhookEvent\WebhookEventRequestInterface;
use App\Modules\Newsletter\Newsletter;
use App\Modules\Subscriber\Services\Internal\ConvertKitSubscriberStatusFixer;
use App\Modules\Subscriber\Services\Internal\RefererRewarder;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberIsReferral;
use App\Shared\RltFields;
use Exception;
use Ramsey\Uuid\UuidInterface;

final readonly class SubscriberWebhookHandler
{
    public function __construct(
        private WebhookEventRequestFactory $webhookEventRequestFactory,
        private EspClientFactory $espClientFactory,
        private RefererRewarder $refererRewarder,
        private ConvertKitSubscriberStatusFixer $convertKitSubscriberStatusFixer,
    ) {
    }

    /**
     * @throws Exception
     */
    public function updateOrCreate(
        UuidInterface $newsletterId,
        array $rawData
    ): bool {
        $data = $this->validateAndMakeDataObject($newsletterId, $rawData);

        $subscriber = $this->updateOrCreateModel($newsletterId, $data);
        $this->updateEspSubscriberFields($data->getId(), $subscriber);
        $this->refererRewarder->handle($subscriber);

        return true;
    }

    private function validateAndMakeDataObject(
        UuidInterface $newsletterId,
        array $rawData
    ): WebhookEventRequestInterface {
        $newsletter = Newsletter::findOrFail($newsletterId);

        return $this->webhookEventRequestFactory->validateAndMake($newsletter->getEspConfig(), $rawData);
    }

    /**
     * @throws Exception
     */
    private function updateOrCreateModel(
        UuidInterface $newsletterId,
        WebhookEventRequestInterface $data,
    ): Subscriber {
        $subscriber = Subscriber::whereNewsletterId($newsletterId)->whereEmail($data->getEmail())->first();

        if (!$subscriber) {
            $subscriber = new Subscriber([
                'newsletter_id' => $newsletterId,
                'email' => $data->getEmail(),
                'is_referral' => SubscriberIsReferral::No,
            ]);
        }

        $subscriber->fill([
            'esp_id' => $data->getId(),
            'status' => $data->getStatus()
        ]);

        $this->convertKitSubscriberStatusFixer->fixStatus($subscriber);

        $subscriber->save();

        return $subscriber;
    }

    private function updateEspSubscriberFields(string $id, Subscriber $subscriber): void
    {
        $espConfig = $subscriber->newsletter->getEspConfig();
        $espClient = $this->espClientFactory->make($espConfig);

        $espClient->updateSubscriberFields($id, RltFields::getSubscriberFields($subscriber));
    }
}
