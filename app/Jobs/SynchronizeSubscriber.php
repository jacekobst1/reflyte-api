<?php

declare(strict_types=1);

namespace App\Jobs;

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
        $subscriber = $subscriberCreator->firstOrCreate($this->espConfig->newsletterId, $this->espSubscriber);
        $espSubscriberUpdater->fillFields($this->espConfig, $this->espSubscriber->id, $subscriber);
    }
}
