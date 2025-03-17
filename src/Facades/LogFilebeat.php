<?php

namespace LaravelKit\Facades;

/**
 * @method static void push(string $index, array $data)
 */
class LogFilebeat extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'FilebeatLogger';
    }
}
