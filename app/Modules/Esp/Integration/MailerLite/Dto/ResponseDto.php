<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\MailerLite\Dto;

use Spatie\LaravelData\Data;

final class ResponseDto extends Data
{
    public function __construct(
        public readonly array $data,
        public readonly ResponseLinksDto $links,
    ) {
    }
}
