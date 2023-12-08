<?php

declare(strict_types=1);

namespace App\Modules\Newsletter\Requests;

use App\Modules\ESP\EspName;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class CreateNewsletterRequest extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,

        #[Required, StringType]
        public readonly string $description,

        #[Required]
        public readonly EspName $esp_name,

        #[Required, StringType]
        public readonly string $esp_api_key,
    ) {
    }
}
