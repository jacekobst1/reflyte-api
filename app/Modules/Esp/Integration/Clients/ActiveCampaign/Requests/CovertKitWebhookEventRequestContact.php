<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\ActiveCampaign\Requests;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

final class CovertKitWebhookEventRequestContact extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $id,

        #[Required, StringType, Email]
        public readonly string $email,
    ) {
    }
}
