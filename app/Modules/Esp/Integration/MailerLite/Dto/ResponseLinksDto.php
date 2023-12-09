<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\MailerLite\Dto;

use Spatie\LaravelData\Data;

final class ResponseLinksDto extends Data
{
    public function __construct(
        public readonly ?string $next,
        public readonly ?string $previous,
        public readonly ?string $first,
        public readonly ?string $last,
    ) {
    }
}
