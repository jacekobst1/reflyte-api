<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Services\Internal;

use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Subscriber\Subscriber;
use Exception;
use Ramsey\Uuid\UuidInterface;

final class SubscriberCreator
{
    /**
     * @throws Exception
     */
    public function firstOrCreate(UuidInterface $newsletterId, EspSubscriberDto $subscriberDto): Subscriber
    {
        $subscriber = Subscriber::whereNewsletterId($newsletterId)
            ->whereEmail($subscriberDto->email)
            ->first();

        if ($subscriber) {
            return $subscriber;
        }

        return Subscriber::create([
            'newsletter_id' => $newsletterId,
            'email' => $subscriberDto->email,
        ]);
    }
}
