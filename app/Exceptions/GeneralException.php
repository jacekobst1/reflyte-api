<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

abstract class GeneralException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json(
            [
                'status' => $this->getCode(),
                'message' => $this->getMessage(),
            ],
            $this->getCode()
        );
    }
}
