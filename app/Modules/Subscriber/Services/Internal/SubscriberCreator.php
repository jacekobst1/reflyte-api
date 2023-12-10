<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Services\Internal;

use App\Modules\Esp\Dto\SubscriberDto;
use App\Modules\Subscriber\Subscriber;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

final class SubscriberCreator
{
    /**
     * @throws Exception
     */
    public function firstOrCreate(SubscriberDto $subscriberDto): Subscriber
    {
        $subscriber = Subscriber::whereNewsletterId(Auth::user()->getNewsletter()->id)
            ->whereEmail($subscriberDto->email)
            ->first();

        if ($subscriber) {
            return $subscriber;
        }

        $refCode = strtolower(Str::random(8));
        $refLink = 'https://join.reflyte.com/' . $refCode;
        $isRef = 'no';
        $refCount = 0;
        $status = 'synchronized';

        return Subscriber::create([
            'newsletter_id' => Auth::user()->getNewsletter()->id,
            'email' => $subscriberDto->email,
            'ref_code' => $refCode,
            'ref_link' => $refLink,
            'is_ref' => $isRef,
            'ref_count' => $refCount,
            'status' => $status,
        ]);
    }
}
