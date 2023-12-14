<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Requests;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class MailerLiteWebhookEventRequest extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $id,

        #[Required, StringType, Email]
        public readonly string $email,

        #[Required, StringType]
        public readonly string $status,
    ) {
    }
}
