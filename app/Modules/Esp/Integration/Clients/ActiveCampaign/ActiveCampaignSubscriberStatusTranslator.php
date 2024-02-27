<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\ActiveCampaign;

use App\Modules\Subscriber\SubscriberStatus;

final class ActiveCampaignSubscriberStatusTranslator
{
    public static function translate(string $status): SubscriberStatus
    {
        return match ((int)$status) {
            1 => SubscriberStatus::Active,
            2 => SubscriberStatus::Unsubscribed,
            default => SubscriberStatus::Other,
        };
    }
}
