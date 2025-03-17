<?php

namespace LaravelKit\Log\Filebeat;

use App\Enums\Events\EventTriggerEnum;
use Illuminate\Database\Eloquent\Model;

class FormatterContextModel implements FormatterContext
{
    public function __construct(
        protected Model  $model,
        protected string $event,
        protected string $triggered,
    )
    {
    }

    public function format(): array
    {
        return [
//            'context' => DbHelper::getContextTitle($this->model),
//            'event' => $this->event,
//            'triggered' => $this->triggered,
        ];
    }
}
