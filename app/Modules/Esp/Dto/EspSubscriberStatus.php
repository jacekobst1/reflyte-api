<?php

declare(strict_types=1);

namespace App\Modules\Esp\Dto;

enum EspSubscriberStatus: string
{
    case Active = 'active';
    case Unsubscribed = 'unsubscribed';
    case Other = 'other';
}
