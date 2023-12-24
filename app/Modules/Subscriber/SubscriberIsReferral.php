<?php

declare(strict_types=1);

namespace App\Modules\Subscriber;

enum SubscriberIsReferral: string
{
    case No = 'no';
    case Yes = 'yes';
}
