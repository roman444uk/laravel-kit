<?php

namespace LaravelKit\Log\Filebeat;

interface FormatterContext
{
    public function format(): array;
}
