<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Services\Internal;

use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberIsRef;
use Ramsey\Uuid\UuidInterface;

class SubscriberFromEspCreator
{
    public function firstOrCreate(UuidInterface $newsletterId, EspSubscriberDto $subscriberDto): Subscriber
    {
        return Subscriber::firstOrCreate(
            [
                'newsletter_id' => $newsletterId,
                'email' => $subscriberDto->email,
            ],
            [
                'status' => $subscriberDto->status,
                'is_ref' => SubscriberIsRef::No,
            ]
        );
    }
}
