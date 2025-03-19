<?php

namespace LaravelKit\Http\Middleware;

use LaravelKit\Helpers\LogHelper;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class RequestDataLogger
{
    public function handle(Request $request, \Closure $next)
    {
        $route = trim(str_replace(
            Route::current()->getPrefix(), '', trim(\Illuminate\Support\Facades\Request::getRequestUri(), '/')
        ), '/');

        file_put_contents(
            LogHelper::getRequestPath($request->requestId),
            json_encode([
                'route' => $route,
                'body' => LogHelper::prepareData($request->all()),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return $next($request);
    }
}
