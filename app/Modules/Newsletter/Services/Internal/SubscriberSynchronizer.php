<?php

declare(strict_types=1);

namespace App\Modules\Newsletter\Services\Internal;

use App\Jobs\SynchronizeSubscriber;
use App\Modules\Esp\Integration\EspClientInterface;
use Exception;
use Illuminate\Support\Facades\Auth;

final class SubscriberSynchronizer
{
    public function __construct(
        private readonly EspClientInterface $espClient,
    ) {
    }

    /**
     * @throws Exception
     */
    public function sync(): void
    {
        [$espSubscribers, $links] = $this->espClient->getSubscribersBatch();
        $espConfig = Auth::user()->getNewsletter()->getEspConfig();

        foreach ($espSubscribers as $espSubscriber) {
            SynchronizeSubscriber::dispatch($espConfig, $espSubscriber);
        }
    }
}
