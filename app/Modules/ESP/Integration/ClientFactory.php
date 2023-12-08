<?php

declare(strict_types=1);

namespace App\Modules\ESP\Integration;

use App\Modules\ESP\EspName;

final class ClientFactory
{
    public function create(EspName $espName, string $apiKey): ClientInterface
    {
        return match ($espName) {
            EspName::MailerLite => new MailerLiteClient($apiKey),
        };
    }
}
