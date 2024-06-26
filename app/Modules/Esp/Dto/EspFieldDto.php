<?php

declare(strict_types=1);

namespace App\Modules\Esp\Dto;

use Spatie\LaravelData\Data;

final class EspFieldDto extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly int|string $key,
    ) {
    }
}
