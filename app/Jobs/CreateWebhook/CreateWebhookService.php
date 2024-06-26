<?php

declare(strict_types=1);

namespace App\Jobs\CreateWebhook;

use App\Modules\Esp\EspName;
use App\Modules\Esp\Integration\Clients\EspClientFactory;
use Ramsey\Uuid\UuidInterface;

final readonly class CreateWebhookService
{
    public function __construct(private EspClientFactory $espClientFactory)
    {
    }

    public function handle(
        UuidInterface $newsletterId,
        EspName $espName,
        string $espApiKey,
        ?string $espApiUrl
    ): void {
        $espClient = $this->espClientFactory->makeSimple($espName, $espApiKey, $espApiUrl);

        $espClient->createWebhook($newsletterId);
    }
}
