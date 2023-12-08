<?php

declare(strict_types=1);

namespace App\Modules\ESP\Integration;

interface ClientInterface
{
    public function apiKeyIsValid(): bool;
}
