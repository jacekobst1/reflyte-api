<?php

declare(strict_types=1);

namespace App\Jobs\SynchronizeSubscriber;

use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Esp\Services\EspSubscriberUpdater;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;
use App\Modules\Subscriber\Services\Internal\SubscriberFromEspCreator;

final readonly class SynchronizeSubscriberService
{
    public function __construct(
        private SubscriberFromEspCreator $subscriberCreator,
        private EspSubscriberUpdater $espSubscriberUpdater,
    ) {
    }

    public function handle(
        NewsletterEspConfig $espConfig,
        EspSubscriberDto $espSubscriber,
    ): void {
        $subscriber = $this->subscriberCreator->firstOrCreate($espConfig->newsletterId, $espSubscriber);
        $this->espSubscriberUpdater->fillFields($espConfig, $espSubscriber->id, $subscriber);
    }
}
