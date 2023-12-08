<?php

declare(strict_types=1);

namespace App\Modules\ESP\Integration;

use App\Modules\ESP\EspName;
use App\Modules\ESP\Integration\MailerLite\MailerLiteClient;

final class ClientFactory
{
    public function make(EspName $espName, string $apiKey): ClientInterface
    {
        return match ($espName) {
            EspName::MailerLite => new MailerLiteClient($apiKey),
        };
    }
}
