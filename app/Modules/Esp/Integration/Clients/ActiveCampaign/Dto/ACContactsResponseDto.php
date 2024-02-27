<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\ActiveCampaign\Dto;

use Spatie\LaravelData\Data;

final class ACContactsResponseDto extends Data
{
    public function __construct(
        public readonly array $score_values,
        public readonly array $contacts,
        public readonly ACContactsResponseMetaDto $meta,
    ) {
    }
}
