<?php

declare(strict_types=1);

namespace App\Modules\Subscriber;

enum SubscriberStatus: string
{
    case Received = 'received';
    case Active = 'active';
    case Unsubscribed = 'unsubscribed';
    case Other = 'other';
}
