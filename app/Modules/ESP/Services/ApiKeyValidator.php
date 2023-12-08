<?php

declare(strict_types=1);

namespace App\Modules\ESP\Services;

use App\Modules\ESP\EspName;
use App\Modules\ESP\Integration\ClientFactory;

class ApiKeyValidator
{
    public function __construct(private readonly ClientFactory $clientFactory)
    {
    }

    public function apiKeyIsValid(EspName $espName, string $apiKey): bool
    {
        $client = $this->clientFactory->create($espName, $apiKey);

        return $client->apiKeyIsValid();
    }
}
