<?php

declare(strict_types=1);

namespace App\Jobs\IntegrateWithEsp;

use App\Jobs\SynchronizeSubscriber\SynchronizeSubscriberJob;
use App\Modules\Esp\Integration\EspClientFactory;
use App\Modules\Esp\Integration\EspClientInterface;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;

class IntegrateWithEspService
{
    private int $commandCounter = 0;
    private EspClientInterface $espClient;
    private NewsletterEspConfig $espConfig;

    public function __construct(private readonly EspClientFactory $espClientFactory)
    {
    }

    public function handle(NewsletterEspConfig $espConfig): void
    {
        $this->espClient = $this->espClientFactory->make($espConfig->espName, $espConfig->espApiKey);
        $this->espConfig = $espConfig;

        $this->process();
    }

    private function process(string $url = null): void
    {
        [$espSubscribers, $links] = $this->espClient->getSubscribersBatch($url);

        foreach ($espSubscribers as $espSubscriber) {
            SynchronizeSubscriberJob::dispatch($this->espConfig, $espSubscriber)
                ->delay(now()->addSeconds($this->commandCounter));

            $this->commandCounter++;
        }

        if ($links->next) {
            $this->process($url);
        }
    }
}
