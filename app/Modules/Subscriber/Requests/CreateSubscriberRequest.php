<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Requests;

use Spatie\LaravelData\Attributes\Validation\AlphaNumeric;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Lowercase;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class CreateSubscriberRequest extends Data
{
    public function __construct(
        #[Required, Email]
        public readonly string $email,

        #[Required, StringType, AlphaNumeric, Lowercase, Size(10)]
        public readonly string $ref_code,
    ) {
    }
}
