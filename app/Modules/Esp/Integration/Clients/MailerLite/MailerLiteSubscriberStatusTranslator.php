<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\MailerLite;

use App\Modules\Subscriber\SubscriberStatus;

final class MailerLiteSubscriberStatusTranslator
{
    public static function translate(string $status): SubscriberStatus
    {
        return match ($status) {
            'active' => SubscriberStatus::Active,
            'unsubscribed' => SubscriberStatus::Unsubscribed,
            default => SubscriberStatus::Other,
        };
    }
}
