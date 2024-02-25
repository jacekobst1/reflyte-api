<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

trait MakeRequestTrait
{
    private function makeRequest(): PendingRequest
    {
        $request = Http::acceptJson()->baseUrl($this->getApiUrl());

        $this->addAuthToRequest($request);

        return $request;
    }

    private function addAuthToRequest(PendingRequest $request): void
    {
        match ($this->getAuthType()) {
            AuthType::AuthorizationHeaderBearerToken => $request->withToken($this->getApiKey()),
            AuthType::QueryParameterApiSecret => $request->withQueryParameters([
                'api_secret' => $this->getApiKey(),
            ]),
        };
    }

    abstract private function getAuthType(): AuthType;

    abstract private function getApiKey(): string;

    abstract private function getApiUrl(): string;
}
