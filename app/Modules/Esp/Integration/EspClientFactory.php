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
        // TODO App::make(MailerLiteEspClient::class, ['apiKey' => $apiKey]);
        return match ($espConfig->espName) {
            EspName::MailerLite => new MailerLiteEspClient($espConfig->espApiKey),
        };
    }
}
