<?php

declare(strict_types=1);

namespace App\Modules\Team\Requests;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class CreateTeamRequest extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,
    ) {
    }
}
