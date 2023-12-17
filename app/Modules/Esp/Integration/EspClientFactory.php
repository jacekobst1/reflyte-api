<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration;

use App\Modules\Esp\EspName;
use App\Modules\Esp\Integration\MailerLite\MailerLiteEspClient;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;

class EspClientFactory
{
    public function make(NewsletterEspConfig $espConfig): EspClientInterface
    {
        return $this->makeSimple($espConfig->espName, $espConfig->espApiKey);
    }

    public function makeSimple(EspName $espName, string $apiKey): EspClientInterface
    {
        return match ($espName) {
            EspName::MailerLite => new MailerLiteEspClient($apiKey),
        };
    }
}
