<?php

declare(strict_types=1);

namespace App\Modules\ESP\Integration;

final class MailerLiteClient implements ClientInterface
{
    use MakeRequestTrait;

    private string $baseUrl = 'https://connect.mailerlite.com/api';

    public function __construct(protected readonly string $apiKey)
    {
    }

    public function apiKeyIsValid(): bool
    {
        $response = $this->makeRequest()->get('groups?page=1000');

        return $response->successful();
    }
}
