<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;

final class ConflictException extends GeneralException
{
    public function __construct(string $message = 'Conflict')
    {
        parent::__construct($message, JsonResponse::HTTP_CONFLICT);
    }
}
