<?php

namespace LaravelKit\Foundation\Bus;

use Illuminate\Foundation\Bus\PendingDispatch;

trait Dispatchable
{
    public static function dispatch(...$arguments)
    {
        $static = new static(...$arguments);

        $static->storeRequest();

        return new PendingDispatch($static);
    }
}
