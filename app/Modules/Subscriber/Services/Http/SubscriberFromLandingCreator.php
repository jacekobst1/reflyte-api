<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Services\Http;

use App\Exceptions\BadRequestException;
use App\Modules\Subscriber\Requests\CreateSubscriberRequest;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberIsRef;
use App\Modules\Subscriber\SubscriberStatus;

final class SubscriberFromLandingCreator
{
    /**
     * @throws BadRequestException
     */
    public function create(CreateSubscriberRequest $data): Subscriber
    {
        $referer = Subscriber::whereRefCode($data->ref_code)->first();

        if (!$referer) {
            throw new BadRequestException('Invalid ref code');
        }

        return Subscriber::create([
            'referer_subscriber_id' => $referer->id,
            'newsletter_id' => $referer->newsletter_id,
            'email' => $data->email,
            'status' => SubscriberStatus::Received,
            'is_ref' => SubscriberIsRef::Yes,
        ]);
    }
}
