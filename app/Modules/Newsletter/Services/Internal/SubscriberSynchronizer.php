<?php

declare(strict_types=1);

namespace App\Modules\Newsletter\Services\Internal;

use App\Modules\ESP\Integration\ClientFactory;

final class SubscriberSynchronizer
{
    public function __construct(private readonly ClientFactory $clientFactory)
    {
    }

    public function sync(string $espName, string $apiKey): void
    {
        $espClient = $this->clientFactory->make($espName, $apiKey);

        $subscribers = $espClient->getAllSubscribers();

        foreach ($subscribers as $subscriber) {
            // create subscriber in database
            // generate special fields for him
            // create fields (if not exists) in ESP
        }
    }
}
