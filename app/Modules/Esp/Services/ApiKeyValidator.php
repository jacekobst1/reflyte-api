<?php

declare(strict_types=1);

namespace App\Modules\Esp\Services;

use App\Modules\Esp\EspName;
use App\Modules\Esp\Integration\EspClientFactory;

class ApiKeyValidator
{
    public function __construct(private readonly EspClientFactory $clientFactory)
    {
    }

    public function apiKeyIsValid(EspName $espName, string $apiKey): bool
    {
        $client = $this->clientFactory->make($espName, $apiKey);

        return $client->apiKeyIsValid();
    }
}
