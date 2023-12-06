<?php

declare(strict_types=1);

namespace App\Shared\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Enumerable;

final class JsonResp
{
    public static function success(array|Enumerable|null $data = []): JsonResponse
    {
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Success',
            'data' => $data,
        ], JsonResponse::HTTP_OK);
    }

    public static function created(array|Enumerable|null $data = []): JsonResponse
    {
        return response()->json([
            'status' => JsonResponse::HTTP_CREATED,
            'message' => 'Created',
            'data' => $data,
        ], JsonResponse::HTTP_CREATED);
    }
}