<?php

namespace LaravelKit\Log\Filebeat;

use Illuminate\Http\Request;

class FormatterContextRequest implements FormatterContext
{
    public function __construct(
        protected Request                 $request,
        protected string                  $event,
        protected string $triggered,
    )
    {
    }

    public function format(): array
    {
        return [
            'route' => $this->request->route(),
            'payload' => $this->request->all(),
            'event' => $this->triggered . '-' . $this->event,
            'triggered' => $this->triggered,
        ];
    }
}
