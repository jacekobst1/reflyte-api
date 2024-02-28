<?php

declare(strict_types=1);

namespace App\Jobs\CreateWebhook;

use App\Modules\Esp\EspName;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\UuidInterface;

final class CreateWebhookJob implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function backoff(): array
    {
        return [120, 480, 1200, 3600];
    }

    public function __construct(
        private readonly UuidInterface $newsletterId,
        private readonly EspName $espName,
        private readonly string $espApiKey,
        private readonly ?string $espApiUrl,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(CreateWebhookService $service): void
    {
        $service->handle($this->newsletterId, $this->espName, $this->espApiKey, $this->espApiUrl);
    }
}
