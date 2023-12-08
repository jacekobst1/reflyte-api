<?php

declare(strict_types=1);

namespace App\Modules\ESP\Integration;

use Spatie\LaravelData\DataCollection;

interface ClientInterface
{
    public function apiKeyIsValid(): bool;

    public function getAllSubscribers(): DataCollection;
}
