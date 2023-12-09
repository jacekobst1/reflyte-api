<?php

declare(strict_types=1);

namespace App\Modules\ESP\Services;

use App\Modules\ESP\EspName;
use App\Modules\ESP\Integration\EspClientFactory;

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
