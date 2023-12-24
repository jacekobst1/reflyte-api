<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\ConvertKit;

use App\Modules\Esp\Dto\EspSubscriberStatus;

final class ConvertKitSubscriberStatusTranslator
{
    public static function translate(string $status): EspSubscriberStatus
    {
        return match ($status) {
            'active' => EspSubscriberStatus::Active,
            'cancelled' => EspSubscriberStatus::Unsubscribed,
            default => EspSubscriberStatus::Other,
        };
    }
}
