<?php

namespace LaravelKit\Traits\Requests;

trait DefaultValuesTrait
{
    /**
     * Assign default values to empty request fields.
     */
    public function defaultValues(array $defaultValues = []): static
    {
        $self = $this;

        collect(array_keys($this->rules()))->each(function (string $fieldName) use (&$self, $defaultValues) {
            if ($self->$fieldName === null) {
                $self->$fieldName = $defaultValues[$fieldName] ?? null;
            }
        });

        return $this;
    }
}

