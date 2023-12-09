<?php

declare(strict_types=1);

namespace App\Modules\ESP\Integration;

use App\Modules\ESP\Dto\FieldDto;
use App\Modules\ESP\Dto\SubscriberDto;
use Spatie\LaravelData\DataCollection;

interface EspClientInterface
{
    public function apiKeyIsValid(): bool;

    /**
     * @return DataCollection<array-key, SubscriberDto>
     */
    public function getAllSubscribers(): DataCollection;

    /**
     * @return DataCollection<array-key, FieldDto>
     */
    public function getAllFields(): DataCollection;

    public function createField(string $name, string $type): bool;
}
