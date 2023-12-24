<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\ConvertKit\Requests;

use App\Modules\Esp\Integration\Clients\ConvertKit\ConvertKitSubscriberStatusTranslator;
use App\Modules\Esp\Integration\Clients\MailerLite\WebhookEventRequestTrait;
use App\Modules\Esp\Integration\WebhookEvent\WebhookEventRequestInterface;
use App\Modules\Subscriber\SubscriberStatus;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

final class CovertKitWebhookEventRequest extends Data implements WebhookEventRequestInterface
{
    use WebhookEventRequestTrait;

    private readonly SubscriberStatus $statusEnum;

    public function __construct(
        #[Required, StringType]
        public readonly string $id,

        #[Required, StringType, Email]
        public readonly string $email,

        #[Required, StringType]
        string $state,
    ) {
        $this->statusEnum = ConvertKitSubscriberStatusTranslator::translate($state);
    }
}
