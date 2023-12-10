<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Exceptions\ConflictException;
use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Esp\Services\EspSubscriberUpdater;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;
use App\Modules\Subscriber\Services\Internal\SubscriberCreator;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SynchronizeSubscriber implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [120, 480, 1200, 3600];
    }

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly NewsletterEspConfig $espConfig,
        private readonly EspSubscriberDto $espSubscriber
    ) {
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(
        SubscriberCreator $subscriberCreator,
        EspSubscriberUpdater $espSubscriberUpdater,
    ): void {
        throw new ConflictException('test failed job');
        $subscriber = $subscriberCreator->firstOrCreate($this->espConfig->newsletterId, $this->espSubscriber);
        $espSubscriberUpdater->fillFields($this->espConfig, $this->espSubscriber->id, $subscriber);
    }
}
