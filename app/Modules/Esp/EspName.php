<?php

declare(strict_types=1);

namespace App\Modules\Esp;

enum EspName: string
{
    case MailerLite = 'mailer_lite';
    case ConvertKit = 'convert_kit';
    case ActiveCampaign = 'active_campaign';
}
