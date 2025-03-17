<?php

namespace LaravelKit\Log;

use Illuminate\Support\Facades\Log;

class FilebeatLogger
{
    public static function push(string $index, array $data): void
    {
        Log::channel('daily-' . $index)->info(null, [
            'data' => $data,
        ]);
    }
}
