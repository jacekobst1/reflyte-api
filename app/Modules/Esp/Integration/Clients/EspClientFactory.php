<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients;

use App\Modules\Esp\EspName;
use App\Modules\Esp\Integration\Clients\ActiveCampaign\ActiveCampaignClient;
use App\Modules\Esp\Integration\Clients\ConvertKit\ConvertKitClient;
use App\Modules\Esp\Integration\Clients\MailerLite\MailerLiteClient;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;

class EspClientFactory
{
    public function make(NewsletterEspConfig $espConfig): EspClientInterface
    {
        return $this->makeSimple($espConfig->espName, $espConfig->espApiKey, $espConfig->espApiUrl);
    }

    public function makeSimple(EspName $espName, string $apiKey, ?string $apiUrl): EspClientInterface
    {
        return match ($espName) {
            EspName::MailerLite => new MailerLiteClient($apiKey),
            EspName::ConvertKit => new ConvertKitClient($apiKey),
            EspName::ActiveCampaign => new ActiveCampaignClient($apiKey, $apiUrl),
        };
    }
}
