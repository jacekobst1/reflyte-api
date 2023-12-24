<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\ConvertKit\Dto;

use Spatie\LaravelData\Data;

final class CKSubscribersResponseDto extends Data
{
    public function __construct(
        public readonly int $total_subscribers,
        public readonly int $page,
        public readonly int $total_pages,
        public readonly array $subscribers,
    ) {
    }
}
