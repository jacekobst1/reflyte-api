<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients;

enum AuthType
{
    case AuthorizationHeaderBearerToken;
    case AuthorizationHeaderApiToken;
    case QueryParameterApiSecret;
}
