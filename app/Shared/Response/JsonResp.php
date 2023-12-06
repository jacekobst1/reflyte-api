<?php

declare(strict_types=1);

namespace App\Shared\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class JsonResp
{
    /**
     * @param array<non-empty-string, mixed>|ResourceCollection|JsonResource|null $data
     * @return JsonResponse
     */
    public static function success(array|ResourceCollection|JsonResource|null $data = []): JsonResponse
    {
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Success',
            'data' => $data,
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @param array<non-empty-string, mixed>|null $data
     * @return JsonResponse
     */
    public static function created(array|null $data = []): JsonResponse
    {
        return response()->json([
            'status' => JsonResponse::HTTP_CREATED,
            'message' => 'Created',
            'data' => $data,
        ], JsonResponse::HTTP_CREATED);
    }
}
