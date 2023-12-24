<?php

declare(strict_types=1);

namespace App\Jobs\IntegrateWithEsp;

use App\Modules\Newsletter\Vo\NewsletterEspConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

final class IntegrateWithEspJob implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function backoff(): array
    {
        return [600, 1800, 3600, 7200];
    }

    public function __construct(private readonly NewsletterEspConfig $espConfig)
    {
    }

    /**
     * @throws Throwable
     */
    public function handle(IntegrateWithEspService $service): void
    {
        $service->handle($this->espConfig);
    }
}
