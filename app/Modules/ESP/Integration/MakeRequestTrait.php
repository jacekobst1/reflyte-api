<?php

declare(strict_types=1);

namespace App\Modules\ESP\Integration;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

trait MakeRequestTrait
{
    private function makeRequest(): PendingRequest
    {
        return Http::acceptJson()
            ->withToken($this->apiKey)
            ->baseUrl($this->baseUrl);
    }
}
