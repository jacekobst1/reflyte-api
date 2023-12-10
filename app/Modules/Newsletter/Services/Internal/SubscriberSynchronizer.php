<?php

declare(strict_types=1);

namespace App\Modules\Newsletter\Services\Internal;

use App\Modules\Esp\Integration\EspClientInterface;
use App\Modules\Esp\Services\EspSubscriberUpdater;
use App\Modules\Subscriber\Services\Internal\SubscriberCreator;
use Exception;

final class SubscriberSynchronizer
{
    public function __construct(
        private readonly EspClientInterface $espClient,
        private readonly SubscriberCreator $subscriberCreator,
        private readonly EspSubscriberUpdater $espSubscriberUpdater,
    ) {
    }

    /**
     * @throws Exception
     */
    public function sync(): void
    {
        $espSubscribers = $this->espClient->getAllSubscribers();

        foreach ($espSubscribers as $espSubscriber) {
            $subscriber = $this->subscriberCreator->firstOrCreate($espSubscriber);
            $this->espSubscriberUpdater->fillFields($espSubscriber->id, $subscriber);
        }
    }
}
