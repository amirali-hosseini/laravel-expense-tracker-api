<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Controller
{
    public function paginatedResponse(LengthAwarePaginator $paginator, string $resourceClass, array $additionalData = [])
    {
        return $this->jsonResponse([
            'data' => $resourceClass::collection($paginator)->toArray(request()),
            'meta' => array_merge([
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ], $additionalData),
        ]);
    }

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

    public function checkUser(Model $model): bool
    {
        return $model->user()->is(auth()->user());
    }
}
