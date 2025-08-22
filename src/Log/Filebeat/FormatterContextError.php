<?php

namespace LaravelKit\Log\Filebeat;

class FormatterContextError implements FormatterContext
{
    public function __construct(
        protected $message,
        protected array $logData = [],
        protected string $event,
        protected string $triggered,
    )
    {
    }

    public function format(): array
    {
        return [
            'exception' => [
                'route' => request()?->route()?->uri,
                'message' => $this->message,
            ]
        ];
    }
}
