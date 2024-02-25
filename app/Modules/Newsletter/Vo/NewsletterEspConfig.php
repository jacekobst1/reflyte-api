<?php

declare(strict_types=1);

namespace App\Modules\Newsletter\Vo;

use App\Modules\Esp\EspName;
use Ramsey\Uuid\UuidInterface;

final readonly class NewsletterEspConfig
{
    public function __construct(
        public UuidInterface $newsletterId,
        public EspName $espName,
        public string $espApiKey,
        public ?string $espApiUrl = null,
    ) {
    }
}
