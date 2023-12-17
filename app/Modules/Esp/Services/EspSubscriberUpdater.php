<?php

declare(strict_types=1);

namespace App\Modules\Esp\Services;

use App\Modules\Esp\Integration\EspClientFactory;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;
use App\Modules\Subscriber\Subscriber;
use App\Shared\RltFields;

class EspSubscriberUpdater
{
    public function __construct(private readonly EspClientFactory $espClientFactory)
    {
    }

    public function fillFields(NewsletterEspConfig $espConfig, string $id, Subscriber $subscriber): void
    {
        $espClient = $this->espClientFactory->make($espConfig);

        $espClient->updateSubscriber($id, [
            'fields' => RltFields::getSubscriberFields($subscriber),
        ]);
    }
}
