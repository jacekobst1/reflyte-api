<?php

declare(strict_types=1);

namespace App\Modules\ESP\Integration;

use App\Modules\ESP\EspName;
use App\Modules\ESP\Integration\MailerLite\MailerLiteEspClient;

final class EspClientFactory
{
    public function make(EspName $espName, string $apiKey): EspClientInterface
    {
        return match ($espName) {
            EspName::MailerLite => new MailerLiteEspClient($apiKey),
        };
    }
}
