<?php

declare(strict_types=1);

namespace App\Modules\Newsletter\Services\Internal;

use App\Modules\ESP\Integration\EspClientInterface;

final class SubscriberSynchronizer
{
    public function __construct(private readonly EspClientInterface $espClient)
    {
    }

    public function sync(): void
    {
        $subscribers = $this->espClient->getAllSubscribers();

        foreach ($subscribers as $subscriber) {
            // create subscriber in database
            // generate special fields for him
            // fill fields for every subscriber
        }
    }
}
