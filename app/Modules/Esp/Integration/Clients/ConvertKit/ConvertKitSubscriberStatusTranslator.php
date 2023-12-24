<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\ConvertKit;

use App\Modules\Subscriber\SubscriberStatus;

final class ConvertKitSubscriberStatusTranslator
{
    public static function translate(string $status): SubscriberStatus
    {
        return match ($status) {
            'active' => SubscriberStatus::Active,
            'cancelled' => SubscriberStatus::Unsubscribed,
            default => SubscriberStatus::Other,
        };
    }
}
