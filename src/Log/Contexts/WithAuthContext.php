<?php

namespace LaravelKit\Log\Contexts;

use Illuminate\Log\Logger;

class WithAuthContext
{
    public function __invoke(Logger $logger)
    {
        $logger->withContext([
            'auth' => [
                'user' => [
                    'id' => auth()->user()->id,
                ],
                'ip' => request()?->ip(),
            ]
        ]);
    }
}
