<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\ConvertKit\Requests;

use App\Modules\Esp\Integration\Clients\ConvertKit\ConvertKitSubscriberStatusTranslator;
use App\Modules\Subscriber\SubscriberStatus;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

final class CovertKitWebhookEventRequestSubscriber extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $id,

        #[Required, StringType, Email]
        public readonly string $email_address,

        #[Required, StringType]
        public readonly string $state,
    ) {
    }

    public function getStatusEnum(): SubscriberStatus
    {
        return ConvertKitSubscriberStatusTranslator::translate($this->state);
    }
}
