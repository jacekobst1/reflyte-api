<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Services\Http;

use App\Exceptions\BadRequestException;
use App\Modules\Subscriber\Requests\CreateSubscriberRequest;
use App\Modules\Subscriber\Subscriber;

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

        $subscriber = Subscriber::create([
            'referer_subscriber_id' => $referer->id,
            'newsletter_id' => $referer->newsletter_id,
            'email' => $data->email,
        ]);

        // TODO init reward awarding mechanism

        return $subscriber;
    }
}
