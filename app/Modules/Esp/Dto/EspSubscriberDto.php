<?php

declare(strict_types=1);

namespace App\Modules\Esp\Dto;

use App\Modules\Subscriber\SubscriberStatus;
use Spatie\LaravelData\Data;

final class EspSubscriberDto extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly SubscriberStatus $status
    ) {
    }
}
