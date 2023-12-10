<?php

declare(strict_types=1);

namespace App\Modules\Newsletter\Vo;

use App\Modules\Esp\EspName;
use Ramsey\Uuid\UuidInterface;

// TODO make every classes like this readonly
final class NewsletterEspConfig
{
    public function __construct(
        public readonly UuidInterface $newsletterId,
        public readonly EspName $espName,
        public readonly string $espApiKey,
    ) {
    }
}
