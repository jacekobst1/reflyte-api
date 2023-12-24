<?php

declare(strict_types=1);

namespace App\Jobs\IntegrateWithEsp;

use App\Jobs\CreateWebhook\CreateWebhookJob;
use App\Jobs\SynchronizeSubscriber\SynchronizeSubscriberJob;
use App\Modules\Esp\Integration\EspClientFactory;
use App\Modules\Esp\Integration\EspClientInterface;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;
use Illuminate\Bus\Batch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Throwable;

final class IntegrateWithEspService
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
        $this->globalDelay = $numberOfFetchCommands = (int)ceil(
            $subscribersCount / $this->espClient->getLimitOfSubscribersBatch()
        );

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

    private function addJobsToArray(?array $previousResponse = null): void
    {
        $this->sleep();
        [$espSubscribers, $nextBatchExists, $response] = $this->espClient->getSubscribersBatch($previousResponse);

        foreach ($espSubscribers as $espSubscriber) {
            $job = new SynchronizeSubscriberJob($this->espConfig, $espSubscriber);
            $this->synchronizeSubscriberJobs[] = $job->delay($this->getDelay());
            $this->commandCounter++;
        }

        if ($nextBatchExists) {
            $this->addJobsToArray($response);
        }
    }

    private function extractEspConfig(NewsletterEspConfig $espConfig): array
    {
        return [$espConfig->newsletterId, $espConfig->espName, $espConfig->espApiKey];
    }

    /**
     * Seconds * 1000 = milliseconds
     * Seconds * 1000 * 1000 = microseconds
     */
    private function sleep(): void
    {
        $safeIntervalInMicroseconds = (int)($this->espClient->getSafeIntervalBetweenRequests() * 1000 * 1000);

        usleep($safeIntervalInMicroseconds);
    }

    /**
     * Delay is calculated based on the number of requests sent to ESP.
     * Global delay = number of seconds that took fetching all subscribers from ESP.
     * Command counter = number of update requests sent to ESP.
     * Safe interval between requests = number of seconds that should pass between requests to ESP.
     */
    private function getDelay(): Carbon
    {
        $globalDelayInSeconds = $this->globalDelay;
        $globalDelayInMilliseconds = $globalDelayInSeconds * 1000;

        $delayBetweenRequestsInSeconds = $this->commandCounter * $this->espClient->getSafeIntervalBetweenRequests();
        $delayBetweenRequestsInMilliseconds = $delayBetweenRequestsInSeconds * 1000;

        $delay = (int)($globalDelayInMilliseconds + $delayBetweenRequestsInMilliseconds);

        return now()->addMilliseconds($delay);
    }
}
