<?php

declare(strict_types=1);

namespace App\Modules\ESP\Integration;

use Spatie\LaravelData\DataCollection;

interface EspClientInterface
{
    public function apiKeyIsValid(): bool;

    public function getAllSubscribers(): DataCollection;

    public function getAllFields(): DataCollection;

    public function createField(string $name, string $type): bool;
}
