<?php

namespace LaravelKit\Log\Contexts;

use Illuminate\Log\Logger;

class WithEnvironmentContext
{
    public function __invoke(Logger $logger)
    {
        $logger->withContext([
            'environment' => env('APP_ENV'),
        ]);
    }
}
