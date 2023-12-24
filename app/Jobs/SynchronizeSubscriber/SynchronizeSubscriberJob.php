<?php

declare(strict_types=1);

namespace App\Jobs\SynchronizeSubscriber;

use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;
use Illuminate\Queue\SerializesModels;

final class SynchronizeSubscriberJob implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public function backoff(): array
    {
        return [120, 480, 1200, 3600];
    }

    public function __construct(
        private readonly NewsletterEspConfig $espConfig,
        private readonly EspSubscriberDto $espSubscriber
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(SynchronizeSubscriberService $service): void
    {
        $service->handle($this->espConfig, $this->espSubscriber);
    }

    public function middleware(): array
    {
        return [new SkipIfBatchCancelled()];
    }
}
