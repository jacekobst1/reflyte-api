<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\ActiveCampaign\Dto;

use Spatie\LaravelData\Data;

final class ACContactsResponseMetaPageInputDto extends Data
{
    public function __construct(
        public readonly int $limit,
        public readonly int $offset,
    ) {
    }
}
