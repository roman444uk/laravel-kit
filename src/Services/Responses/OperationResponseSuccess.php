<?php

namespace LaravelKit\Services\Responses;

class OperationResponseSuccess extends OperationResponse
{
    public function __construct(array $data = [], string $message = null, ?int $httpCode = null)
    {
        parent::__construct(true, $data, $message, [], $httpCode);
    }
}
