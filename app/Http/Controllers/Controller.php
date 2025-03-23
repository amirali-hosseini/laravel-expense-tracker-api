<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    public function jsonResponse(array $responseData, bool $isSuccessful = true, int $statusCode = 200): JsonResponse
    {
        $responseData['success'] = $isSuccessful;

        return response()->json($responseData, $statusCode);
    }

    public function unauthenticatedResponse(): JsonResponse
    {
        return $this->jsonResponse([
            'message' => 'Unauthenticated.'
        ], false, 401);
    }
}
