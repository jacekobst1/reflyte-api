<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

trait MakeRequestTrait
{
    private function makeRequest(): PendingRequest
    {
        $request = Http::acceptJson()->baseUrl($this->baseUrl);

        $this->addAuthToRequest($request);

        return $request;
    }

    private function addAuthToRequest(PendingRequest $request): void
    {
        match ($this->getAuthType()) {
            AuthType::AuthorizationHeaderBearerToken => $request->withToken($this->apiKey),
            AuthType::QueryParameterApiSecret => $request->withQueryParameters([
                'api_secret' => $this->apiKey,
            ]),
        };
    }

    abstract public function getAuthType(): AuthType;
}
