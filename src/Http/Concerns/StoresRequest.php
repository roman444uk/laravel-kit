<?php

namespace LaravelKit\Http\Concerns;

use Illuminate\Http\Request;

trait StoresRequest
{
    public ?Request $request;

    protected function storeRequest(): void
    {
        $this->request = request();
    }
}
