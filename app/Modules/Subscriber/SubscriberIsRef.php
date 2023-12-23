<?php

declare(strict_types=1);

namespace App\Modules\Subscriber;

enum SubscriberIsRef: string
{
    case No = 'no';
    case Yes = 'yes';
}
