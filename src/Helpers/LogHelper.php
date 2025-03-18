<?php

namespace LaravelKit\Helpers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class LogHelper
{
    public static function getRequestBodyLogPath(string $requestId): string
    {
        $folder = public_path('logs/requests/' . date('Y-m-d'));
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        return $folder . '/' . $requestId . '.log';
    }

    public static function getRequestBodyLogUrl(string $requestId): string
    {
        return URL::to('logs/requests/' .date('Y-m-d') . '/' . $requestId . '.log');
    }

    public static function prepareData(array $data): array
    {
        // Remove binary data for file requests.
        if (isset($data['binarydata'])) {
            $data['binarydata'] = null;
        }

        return $data;
    }

    public static function generateExceptionId(): string
    {
        return (string) Str::uuid();
    }

    public static function getExceptionLogPath(string $exceptionId): string
    {
        $folder = public_path('logs/exceptions/' . date('Y-m-d'));
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        return $folder . '/' . $exceptionId . '.log';
    }

    public static function getExceptionLogUrl(string $exceptionId): string
    {
        return URL::to('logs/exceptions/' .date('Y-m-d') . '/' . $exceptionId . '.log');
    }

    public static function saveExceptionLog(array $exception): string
    {
        $exceptionId = self::generateExceptionId();
        $filePath = self::getExceptionLogPath($exceptionId);

        file_put_contents($filePath, json_encode($exception, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $exceptionId;
    }
}
