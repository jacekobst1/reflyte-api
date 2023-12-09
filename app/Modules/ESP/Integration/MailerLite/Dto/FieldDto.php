<?php

declare(strict_types=1);

namespace App\Modules\ESP\Integration\MailerLite\Dto;

use Spatie\LaravelData\Data;

final class FieldDto extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $key,
        public readonly string $type,
    ) {
    }
}
