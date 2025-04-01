<?php

namespace LaravelKit\Http\Middleware;

use LaravelKit\Helpers\LogHelper;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class RequestDataLogger
{
    public function handle(Request $request, \Closure $next)
    {
        LogHelper::saveRequestLog($request);

        return $next($request);
    }
}
