<?php

namespace LaravelKit\Log\Filebeat;

use LaravelKit\Helpers\LogHelper;
use Monolog\Formatter\NormalizerFormatter;
use Monolog\LogRecord;

class FilebeatFormatter extends NormalizerFormatter
{
    public function format(LogRecord $record)
    {
        $message = [
            '@timestamp' => $this->normalize($record['datetime']),
            'log' => [
                'level' => $record['level_name'],
                'logger' => $record['channel'],
            ],
        ];

        if (isset($record['context']['auth'])) {
            $message['auth'] = $record['context']['auth'];
        }

        if (isset($record['context']['data'])) {
            $message['data'] = $record['context']['data'];
        }

        if (isset($record['context']['environment'])) {
            $message['environment'] = $record['context']['environment'];
        }

        if (isset($record['context']['request'])) {
            $request = $record['context']['request'];

            if (isset($request['id'])) {
                $request['source'] = LogHelper::getRequestBodyLogUrl($request['id']);
            }

            $message['request'] = $request;
        }

        if (!empty($record['message'])) {
            $message['message'] = $record['message'];
        }

        $this->collectContexts($record, $message);

        return $this->toJson($message) . "\n";
    }

    protected function collectContexts(LogRecord $record, array &$message): void
    {
        if (!isset($record['context'])) {
            return;
        }

        /**
         * Collect custom logs.
         */
        if (isset($record['context']['formatter']) && $record['context']['formatter'] instanceof FormatterContext) {
            /** @var FormatterContext $formatter */
            $formatter = $record['context']['formatter'];

            $message = array_merge($message, $formatter->format());
        }

        /**
         * Collect exceptions and error handlers.
         */
        if (isset($record['context']) && isset($record['context']['exception'])) {
            $exception = (new FormatterContextException($record['context']['exception']))->format();

            $exceptionId = LogHelper::saveExceptionLog($exception);
            $exception['exception']['source'] = LogHelper::getExceptionLogUrl($exceptionId);

            unset($exception['exception']['trace'], $exception['exception']['previous']);

            $message = array_merge($message, $exception);
        }
    }
}
