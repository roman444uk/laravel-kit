<?php

namespace LaravelKit\Log\Filebeat;

class FormatterContextException implements FormatterContext
{
    public function __construct(
        protected \Exception|\Error $exception,
        protected array $logData = [],
        protected ?string $event = null,
        protected ?string $triggered = null,
    )
    {
    }

    public function format(): array
    {
        return [
            'exception' => [
                'route' => request()?->route()?->uri,
                'message' => $this->exception->getMessage(),
                'file' => $this->exception->getFile(),
                'line' => $this->exception->getLine(),
                'trace' => $this->exception->getTrace(),
                'previous' => $this->collectPreviousExceptions($this->exception),
            ]
        ];
    }

    protected function collectPreviousExceptions(\Exception|\Error $exception): array
    {
        $previousExceptions = [];

        while ($previous = $exception->getPrevious()) {
            if ($previous) {
                $previousExceptions[] = $previous;
                $exception = $previous;
            }
        };

        return $previousExceptions;
    }
}
