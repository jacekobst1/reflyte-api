<?php

declare(strict_types=1);

namespace App\Jobs\IntegrateWithEsp;

use App\Jobs\CreateWebhook\CreateWebhookJob;
use App\Jobs\SynchronizeSubscriber\SynchronizeSubscriberJob;
use App\Modules\Esp\Integration\EspClientFactory;
use App\Modules\Esp\Integration\EspClientInterface;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class IntegrateWithEspService
{
    private int $commandCounter = 0;
    private int $globalDelay = 0;
    private array $synchronizeSubscriberJobs = [];
    private EspClientInterface $espClient;
    private NewsletterEspConfig $espConfig;

    public function __construct(private readonly EspClientFactory $espClientFactory)
    {
    }

    /**
     * @throws Throwable
     */
    public function handle(NewsletterEspConfig $espConfig): void
    {
        $this->espClient = $this->espClientFactory->make($espConfig);
        $this->espConfig = $espConfig;

        $subscribersCount = $this->getEspSubscribersCount();
        $this->globalDelay = $numberOfFetchCommands = (int)ceil($subscribersCount / 1000);

        $this->addJobsToArray();

        [$newsletterId, $espName, $espApiKey] = $this->extractEspConfig($espConfig);

        Bus::batch($this->synchronizeSubscriberJobs)
            ->then(fn(Batch $batch) => CreateWebhookJob::dispatch($newsletterId, $espName, $espApiKey))
            ->name("Synchronize subscribers | newsletterId: $espConfig->newsletterId")
            ->dispatch();
    }

    private function getEspSubscribersCount(): int
    {
        return $this->espClient->getSubscribersTotalNumber();
    }

    private function addJobsToArray(string $url = null): void
    {
        sleep(1);
        [$espSubscribers, $links] = $this->espClient->getSubscribersBatch($url);

        foreach ($espSubscribers as $espSubscriber) {
            $this->synchronizeSubscriberJobs[] =
                (new SynchronizeSubscriberJob($this->espConfig, $espSubscriber))
                    ->delay(now()->addSeconds($this->globalDelay + $this->commandCounter));

            $this->commandCounter++;
        }

        if ($links->next) {
            $this->addJobsToArray($links->next);
        }
    }

    private function extractEspConfig(NewsletterEspConfig $espConfig): array
    {
        return [$espConfig->newsletterId, $espConfig->espName, $espConfig->espApiKey];
    }
}
