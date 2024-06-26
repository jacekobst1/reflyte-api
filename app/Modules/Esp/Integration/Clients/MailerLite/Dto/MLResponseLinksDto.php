<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\MailerLite\Dto;

use Spatie\LaravelData\Data;

final class MLResponseLinksDto extends Data
{
    public function __construct(
        public readonly ?string $next,
        public readonly ?string $previous,
        public readonly ?string $first,
        public readonly ?string $last,
    ) {
    }
}
