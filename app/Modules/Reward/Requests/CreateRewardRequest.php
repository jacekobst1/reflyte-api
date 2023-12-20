<?php

declare(strict_types=1);

namespace App\Modules\Reward\Requests;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

final class CreateRewardRequest extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,

        #[Required, StringType]
        public readonly string $description,

        #[Required, IntegerType]
        public readonly int $required_points,
    ) {
    }
}
