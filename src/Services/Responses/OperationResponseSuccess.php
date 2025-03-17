<?php

namespace LaravelKit\Services\Responses;

class OperationResponseSuccess extends OperationResponse
{
    public function __construct(array $data = [])
    {
        parent::__construct(true, $data);
    }
}
