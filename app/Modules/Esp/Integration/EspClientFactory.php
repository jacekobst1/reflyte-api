<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration;

use App\Modules\Esp\EspName;
use App\Modules\Esp\Integration\MailerLite\MailerLiteEspClient;

final class EspClientFactory
{
    public function make(EspName $espName, string $apiKey): EspClientInterface
    {
        return match ($espName) {
            EspName::MailerLite => new MailerLiteEspClient($apiKey),
        };
    }
}
