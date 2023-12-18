<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration;

enum AuthType
{
    case AuthorizationHeaderBearerToken;
    case QueryParameterApiSecret;
}
