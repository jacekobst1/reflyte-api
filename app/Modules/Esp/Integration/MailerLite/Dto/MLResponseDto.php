<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\MailerLite\Dto;

use Spatie\LaravelData\Data;

final class MLResponseDto extends Data
{
    public function __construct(
        public readonly array $data,
        public readonly MLResponseLinksDto $links,
        public readonly array $meta,
    ) {
    }
}
