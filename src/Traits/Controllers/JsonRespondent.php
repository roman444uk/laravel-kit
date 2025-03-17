<?php

namespace LaravelKit\Traits\Controllers;

use LaravelKit\Services\Responses\OperationResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

trait   JsonRespondent
{
    protected function errorResponse(?string $message = '', array $errors = [], int $code = 403): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'data' => [],
        ], $code);
    }

    protected function successResponse(?string $message = '', array $data = [], int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'errors' => [],
            'data' => $data,
        ], $code);
    }

    protected function operationResponse(OperationResponse $operationResponse, array $withData = [], array $extraData = []): JsonResponse
    {
        if (!$operationResponse->isSuccess()) {
            return $this->errorResponse($operationResponse->getMessage(), $operationResponse->getErrors());
        }

        $data = [];
        collect($withData)->each(function ($key) use (&$data, $operationResponse) {
            $data[$key] = $operationResponse->get($key);

            if (!empty($data[$key]) && $data[$key] instanceof Model) {
                $data[$key] = $data[$key]->toArray();
            }
        });

        return $this->successResponse(null, array_merge($data, $extraData));
    }
}
