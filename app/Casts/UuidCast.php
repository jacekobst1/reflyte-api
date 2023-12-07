<?php

declare(strict_types=1);

namespace App\Casts;

use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Exceptions\CannotCastEnum;
use Spatie\LaravelData\Support\DataProperty;
use Throwable;

class UuidCast implements Cast
{
    /**
     * @throws CannotCastEnum
     * @throws Exception
     */
    public function cast(DataProperty $property, mixed $value, array $context): UuidInterface
    {
        try {
            return Uuid::fromString($value);
        } catch (Throwable $e) {
            throw new Exception("Cannot cast string $value to UUID");
        }
    }
}
