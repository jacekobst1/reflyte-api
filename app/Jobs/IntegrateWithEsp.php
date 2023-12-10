<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Modules\Esp\Integration\EspClientFactory;
use App\Modules\Esp\Integration\EspClientInterface;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class IntegrateWithEsp implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private int $commandCounter = 0;
    private readonly EspClientInterface $espClient;

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [600, 1800, 3600, 7200];
    }

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly NewsletterEspConfig $espConfig)
    {
        /** @var EspClientFactory $espClientFactory */
        $espClientFactory = App::make(EspClientFactory::class);

        $this->espClient = $espClientFactory->make($this->espConfig->espName, $this->espConfig->espApiKey);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->process();
    }

    private function process(string $url = null): void
    {
        [$espSubscribers, $links] = $this->espClient->getSubscribersBatch($url);

        foreach ($espSubscribers as $espSubscriber) {
            SynchronizeSubscriber::dispatch($this->espConfig, $espSubscriber)
                ->delay(now()->addSeconds($this->commandCounter));

            $this->commandCounter++;
        }

        if ($links->next) {
            $this->process($url);
        }
    }
}
