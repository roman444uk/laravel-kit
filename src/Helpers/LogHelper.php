<?php

namespace LaravelKit\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class LogHelper
{
    public static function extractDateFromExceptionId(string $exceptionId): string
    {
        return self::extractDateFromResourceId($exceptionId);
    }

    public static function extractDateFromRequestId(string $requestId): string
    {
        return self::extractDateFromResourceId($requestId);
    }

    public static function extractDateFromResourceId(string $resourceId): string
    {
        return substr($resourceId, 0, 10);
    }



    public static function getExceptionPath(string $exceptionId): string
    {
        return self::getResourcePath($exceptionId, 'exception');
    }

    public static function getRequestPath(string $requestId): string
    {
        return self::getResourcePath($requestId, 'request');
    }

    public static function getResourcePath(string $resourceId, string $type): string
    {
        $folder = public_path('logs/' .  $type. '/' . self::extractDateFromRequestId($resourceId));
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        return $folder . '/' . $resourceId . '.log';
    }



    public static function getExceptionUrl(string $exceptionId): string
    {
        return self::getResourceUrl($exceptionId, 'exception');
    }

    public static function getRequestUrl(string $requestId): string
    {
        return self::getResourceUrl($requestId, 'request');
    }

    public static function getResourceUrl(string $resourceId, string $type): string
    {
        return route('logs.show', [
            'type' => $type,
            'resourceId' => $resourceId,
        ]);
    }



    public static function generateExceptionId(): string
    {
        return self::generateResourceId();
    }

    public static function generateRequestId(): string
    {
        return self::generateResourceId();
    }

    public static function generateResourceId(): string
    {
        return date('Y-m-d_H:i:s_') .  Str::uuid();
    }


    public static function prepareData(array $data): array
    {
        // Remove binary data for file requests.
        if (isset($data['binarydata'])) {
            $data['binarydata'] = null;
        }

        return $data;
    }

    public static function saveExceptionLog(array $exception): string
    {
        $exceptionId = self::generateExceptionId();
        $filePath = self::getExceptionPath($exceptionId);

        file_put_contents($filePath, json_encode($exception, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $exceptionId;
    }

    public static function saveRequestLog(?Request $request): void
    {
        if (!$request) {
            return;
        }

        $route = trim(str_replace(
            Route::current()->getPrefix(), '', trim(\Illuminate\Support\Facades\Request::getRequestUri(), '/')
        ), '/');

        file_put_contents(
            self::getRequestPath($request->requestId),
            json_encode([
                'route' => $route,
                'body' => self::prepareData($request->all()),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
