<?php

declare(strict_types=1);

namespace App\Modules\Subscriber;

enum SubscriberStatus: string
{
    case Received = 'received';
    case Synchronized = 'synchronized';
    case Unsubscribed = 'unsubscribed';
}
