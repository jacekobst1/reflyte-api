<?php

declare(strict_types=1);

namespace App\Modules\ESP\Integration;

use Illuminate\Support\Facades\Http;

final class MailerLiteClient implements ClientInterface
{
    public function __construct(private readonly string $apiKey)
    {
    }

    public function apiKeyIsValid(): bool
    {
        $response = Http::withToken($this->apiKey)->get('https://connect.mailerlite.com/api/groups?page=1000');

        return $response->successful();
    }
}
