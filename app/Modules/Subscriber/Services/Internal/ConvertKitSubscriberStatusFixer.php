<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Services\Internal;

use App\Modules\Esp\EspName;
use App\Modules\Esp\Integration\Clients\EspClientFactory;
use App\Modules\Subscriber\Subscriber;
use Exception;

final readonly class ConvertKitSubscriberStatusFixer
{
    public function __construct(
        private readonly EspClientFactory $espClientFactory,
    ) {
    }

    /**
     * We must fix the status of the subscriber, because the status received in the webhook from ConvertKit is wrong (reverse).
     * When the subscriber subscribes, the status is "inactive".
     * When the subsriber unsubscribes, the status is "active".
     * @throws Exception
     */
    public function fixStatus(Subscriber $subscriberFromWebhook): void
    {
        $espConfig = $subscriberFromWebhook->newsletter->getEspConfig();

        if ($espConfig->espName !== EspName::ConvertKit) {
            return;
        }

        $espClient = $this->espClientFactory->make($subscriberFromWebhook->newsletter->getEspConfig());
        $freshSubscriber = $espClient->getSubscriber($subscriberFromWebhook->esp_id);

        if (!$freshSubscriber) {
            throw new Exception('Subscriber received in webhook, but not found in ESP');
        }

        $subscriberFromWebhook->status = $freshSubscriber->status;
    }
}
