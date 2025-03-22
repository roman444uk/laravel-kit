<?php

namespace LaravelKit\Data\Concerns;

use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionProperty;
use Spatie\LaravelData\Support\Lazy\RelationalLazy;

trait ScenarioData
{
    protected ?string $scenario;

    public function applyScenario(?string $scenario = null): static
    {
        $this->setScenario($scenario);

        $self = $this;

        // Apply scenario to child data models
        collect((new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC))
            ->each(function (ReflectionProperty $property) use ($scenario, &$self) {
                $propertyValue = $self->{$property->getName()};

                if ($propertyValue instanceof RelationalLazy) {
                    $propertyValue = $propertyValue->resolve();
                    $self->{$property->getName()} = $propertyValue;
                }

                if ($propertyValue instanceof \App\Data\Contracts\ScenarioData) {
                    $propertyValue->applyScenario($scenario);
                }

                if ($propertyValue instanceof Collection && $propertyValue->first() instanceof \App\Data\Contracts\ScenarioData) {
                    $propertyValue->each(function ($value) use ($scenario) {
                        if ($value instanceof \App\Data\Contracts\ScenarioData) {
                            $value->applyScenario($scenario);
                        }
                    });
                }
            });

        // Excluding fields from current data model according current scenario
        $excludeFields = method_exists($this, 'exceptFields') ? $this::exceptFields($this->getScenario()) : [];

        if ($excludeFields) {
            $this->except(...$excludeFields);
        }

        return $this;
    }

    public function getScenario(): ?string
    {
        return $this->scenario;
    }

    public function setScenario(?string $scenario = null): static
    {
        $this->scenario = $scenario;

        return $this;
    }

    public function isScenario(array|string $scenario): ?string
    {
        return (is_string($this->scenario) && $this->scenario === $scenario) || (is_array($scenario) && in_array($this->scenario, $scenario));
    }

    public function toArray(): array
    {
        $excludeFields = method_exists($this, 'excludeFields') ? $this::exceptFields($this->getScenario()) : [];

        if ($excludeFields) {
            $this->except(...$excludeFields);
        }

        return parent::toArray();

//        return array_filter(parent::toArray(), function (mixed $data, string $fieldName) use ($excludeFields) {
//            return !in_array($fieldName, $excludeFields);
//        }, ARRAY_FILTER_USE_BOTH);
    }
}
