<?php

declare(strict_types=1);

namespace App\Shared\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class JsonResp
{
    public static function custom(int $status, string $message): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $status);
    }

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

    public static function badRequest(string $message = 'Bad Request'): JsonResponse
    {
        return response()->json([
            'status' => JsonResponse::HTTP_BAD_REQUEST,
            'message' => $message,
        ], JsonResponse::HTTP_BAD_REQUEST);
    }

    public static function resourceNotFound(): JsonResponse
    {
        return response()->json([
            'status' => JsonResponse::HTTP_NOT_FOUND,
            'message' => 'Resource not found',
        ], JsonResponse::HTTP_NOT_FOUND);
    }

    public static function routeNotFound(): JsonResponse
    {
        return response()->json([
            'status' => JsonResponse::HTTP_NOT_FOUND,
            'message' => 'Route not found',
        ], JsonResponse::HTTP_NOT_FOUND);
    }

    public static function unauthorized(): JsonResponse
    {
        return response()->json([
            'status' => JsonResponse::HTTP_FORBIDDEN,
            'message' => 'Unauthorized',
        ], JsonResponse::HTTP_FORBIDDEN);
    }
}
