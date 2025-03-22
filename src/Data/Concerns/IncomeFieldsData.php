<?php

namespace LaravelKit\Data\Concerns;

use LaravelKit\Support\Str;
use Illuminate\Http\Request;

trait IncomeFieldsData
{
    private array $_incomeFields = [];

    public static function from(mixed ...$payloads): static
    {
        // Push income fields if Request instance came
        $request = null;
        if ($payloads[count($payloads) - 1] instanceof Request) {
            $request = array_pop($payloads);
        }

        $fromData = [];
        if (!$request && is_array($payloads[0])) {
            $fromData = $payloads[0];
        }

        /** @var self $instance */
        $instance = static::factory()->from(...$payloads);

        if ($request) {
            $instance->setIncomeFields($request);
        } elseif ($fromData) {
            $instance->setIncomeFields($fromData);
        }

        return $instance;
    }

    public function getIncome(string $field, mixed $defaultValueIfNotIncome = null): mixed
    {
        return $this->isIncome($field) ? $this->$field : $defaultValueIfNotIncome;
    }

    public function isIncome(string $field): bool
    {
        return in_array($field, $this->_incomeFields);
    }

    protected function setIncomeFields(Request|array $request): void
    {
        $collectedFields = null;

        if ($request instanceof Request) {
            $collectedFields = collect(array_keys($request->validated()));
        }

        if (is_array($request)) {
            $collectedFields = collect(array_keys($request));
        }

        if ($collectedFields) {
            $this->_incomeFields = $collectedFields
                ->map(
                    fn(string $field) => Str::toCamelCase($field, '_')
                )
                ->toArray();
        }
    }
}
