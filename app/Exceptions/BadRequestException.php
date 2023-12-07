<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;

final class BadRequestException extends GeneralException
{
    public function __construct(string $message = 'Bad request')
    {
        parent::__construct($message, JsonResponse::HTTP_BAD_REQUEST);
    }
}
