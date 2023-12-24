<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\MailerLite;

use App\Modules\Esp\Dto\EspSubscriberStatus;

final class MailerLiteSubscriberStatusTranslator
{
    public static function translate(string $status): EspSubscriberStatus
    {
        return match ($status) {
            'active' => EspSubscriberStatus::Active,
            'unsubscribed' => EspSubscriberStatus::Unsubscribed,
            default => EspSubscriberStatus::Other,
        };
    }
}
