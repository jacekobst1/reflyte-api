<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration;

use App\Modules\Esp\EspName;
use App\Modules\Esp\Integration\MailerLite\MailerLiteEspClient;

class EspClientFactory
{
    // TODO replace 2 parameters with 1: NewsletterEspConfig
    public function make(EspName $espName, string $apiKey): EspClientInterface
    {
        // TODO App::make(MailerLiteEspClient::class, ['apiKey' => $apiKey]);
        return match ($espName) {
            EspName::MailerLite => new MailerLiteEspClient($apiKey),
        };
    }
}
