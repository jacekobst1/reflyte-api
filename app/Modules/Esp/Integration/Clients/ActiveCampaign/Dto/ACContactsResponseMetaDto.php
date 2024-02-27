<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\ActiveCampaign\Dto;

use Spatie\LaravelData\Data;

final class ACContactsResponseMetaDto extends Data
{
    public function __construct(
        public readonly ACContactsResponseMetaPageInputDto $page_input,
        public readonly string $total,
        public readonly bool $sortable,
    ) {
    }

    public function getTotal(): int
    {
        return (int)$this->total;
    }
}
