<?php

use App\Classes\ServicesResponses\OperationResponse;
use App\Models\Users\User;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

/**
 * System functions.
 */
if (!function_exists('envConfig')) {
    function envConfig($key, $default = null)
    {
        return env($key, $default ?? config('env.' . $key));
    }
}

if (!function_exists('urlModTime')) {
    function urlModTime($path = null, $parameters = [], $secure = null): UrlGenerator|string
    {
        return url($path . '?v=' . filemtime(public_path($path)), $parameters, $secure ?? Request::secure());
    }
}

/**
 * Auth and permissions.
 */
if (!function_exists('can')) {
    function can($abilities, $arguments = [], User $user = null): bool
    {
        return getUser($user)->can($abilities, $arguments);
    }
}

if (!function_exists('getUser')) {
    function getUser(User $user = null): ?User
    {
        return $user ?: auth()->user();
    }
}

/**
 * System controllers and services responses.
 */
if (!function_exists('successJsonResponse')) {
    function successJsonResponse(array $data = [], ?string $message = '', int $httpCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'errors' => [],
            'data' => $data,
        ], $httpCode);
    }
}

if (!function_exists('errorJsonResponse')) {
    function errorJsonResponse(array $errors = [], ?string $message = '', int $httpCode = 403): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'data' => [],
        ], $httpCode);
    }
}

if (!function_exists('successOperationResponse')) {
    function successOperationResponse(array $data = [], ?int $httpCode = null): OperationResponse
    {
        return OperationResponse::success($data, $httpCode);
    }
}

if (!function_exists('errorOperationResponse')) {
    function errorOperationResponse(string $message = null, array $errors = [], array $data = [], ?int $httpCode = null): OperationResponse
    {
        return OperationResponse::error($message, $errors, $data, $httpCode);
    }
}

if (!function_exists('operationJsonResponse')) {
    function operationJsonResponse(
        OperationResponse $operationResponse, ?array $withData = [], array $extraData = []
    ): JsonResponse
    {
        if (!$operationResponse->isSuccess()) {
            return errorJsonResponse(
                $operationResponse->getMessage(), $operationResponse->getErrors(), $operationResponse->getHttpCode() ?? 403
            );
        }

        $data = [];
        collect($withData)->each(function ($key) use (&$data, $operationResponse) {
            $data[$key] = $operationResponse->get($key);

            if (!empty($data[$key]) && $data[$key] instanceof Model) {
                $modelClass = get_class($data[$key]);

                $modelClassParts = explode('\\', $modelClass);
                $modelClassName = array_pop($modelClassParts);
                $modelClassPath = array_pop($modelClassParts);

                /** @var Spatie\LaravelData\Data|string $modelDataClass */
                $modelDataClass = 'App\\Data\\Models\\' . $modelClassPath . '\\' . $modelClassName . 'Data';

                if (class_exists($modelDataClass)) {
                    $data[$key] = $modelDataClass::fromModel($data[$key]);
                } else {
                    $data[$key] = $data[$key]->toArray();
                }
            }
        });

        return successJsonResponse(
            $operationResponse->getMessage(), array_merge($data, $extraData), $operationResponse->getHttpCode() ?? 200
        );
    }
}