<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Shared\Response\JsonResp;
use Exception;
use Illuminate\Http\JsonResponse;

abstract class GeneralException extends Exception
{
    public function render(): JsonResponse
    {
        return JsonResp::custom(
            $this->getCode(),
            $this->getMessage()
        );
    }
}
