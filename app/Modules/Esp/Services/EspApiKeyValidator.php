<?php

declare(strict_types=1);

namespace App\Modules\Esp\Services;

use App\Modules\Esp\EspName;
use App\Modules\Esp\Integration\Clients\EspClientFactory;

class EspApiKeyValidator
{
    public function __construct(private readonly EspClientFactory $clientFactory)
    {
    }

    public function apiKeyIsValid(EspName $espName, string $apiKey, ?string $apiUrl): bool
    {
        $client = $this->clientFactory->makeSimple($espName, $apiKey, $apiUrl);

        return $client->apiKeyIsValid();
    }
}
