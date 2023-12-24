<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients;

use App\Modules\Esp\Dto\EspFieldDto;
use App\Modules\Esp\Dto\EspSubscriberDto;
use Ramsey\Uuid\UuidInterface;
use Spatie\LaravelData\DataCollection;

interface EspClientInterface
{
    /*
    |--------------------------------------------------------------------------
    | Helper methods methods
    |--------------------------------------------------------------------------
    |
    | Methods responsible for providing data for error-less integration with ESP.
    |
    */

    public function getLimitOfSubscribersBatch(): int;

    public function getSafeIntervalBetweenRequests(): float;

    /*
    |--------------------------------------------------------------------------
    | Client methods
    |--------------------------------------------------------------------------
    |
    | Methods responsible for communication with ESP API.
    |
    */

    public function apiKeyIsValid(): bool;

    public function getSubscribersTotalNumber(): int;

    /**
     * @return array{DataCollection<array-key, EspSubscriberDto>, bool, array}
     */
    public function getSubscribersBatch(?array $previousResponse = null): array;

    /**
     * @return DataCollection<array-key, EspFieldDto>
     */
    public function getAllFields(): DataCollection;

    public function createField(string $key, string $type): bool;

    /**
     * @param array<non-empty-string, string|int> $fields
     */
    public function updateSubscriberFields(string $id, array $fields): bool;

    public function createWebhook(UuidInterface $newsletterId): bool;
}
