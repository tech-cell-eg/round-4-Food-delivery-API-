<?php

namespace App;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    //
    protected function successResponse(
        $data = null,
        string $message = '',
        int $statusCode = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'status' => $statusCode
        ], $statusCode);
    }

    protected function errorResponse(
        string $message = '',
        int $statusCode = 400,
        ?array $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'status' => $statusCode
        ], $statusCode);
    }
}
