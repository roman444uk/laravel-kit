<?php

namespace LaravelKit\Log\Contexts;

use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class WithRequestContext
{
    public function __invoke(Logger $logger)
    {
        $sharedContext = Log::sharedContext();

        if (isset($sharedContext['request'])) {
            $logger->withContext([
                'request' => $sharedContext['request']
            ]);
        }
    }
}
