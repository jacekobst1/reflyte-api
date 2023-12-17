<?php

declare(strict_types=1);

namespace App\Jobs\CreateWebhook;

use App\Modules\Esp\EspName;
use App\Modules\Esp\Integration\EspClientFactory;
use Ramsey\Uuid\UuidInterface;

class CreateWebhookService
{
    public function __construct(private readonly EspClientFactory $espClientFactory)
    {
    }

    public function handle(UuidInterface $newsletterId, EspName $espName, string $espApiKey): void
    {
        $espClient = $this->espClientFactory->makeSimple($espName, $espApiKey);

        $espClient->createWebhook($newsletterId);
    }
}
