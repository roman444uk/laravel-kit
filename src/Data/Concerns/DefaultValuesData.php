<?php

namespace LaravelKit\Data\Concerns;

trait DefaultValuesData
{
    /**
     * Assign default values to empty data model fields.
     */
    public function defaultValues(array $defaultValues = []): static
    {
        $self = $this;

        collect($defaultValues)->each(function (mixed $fieldValue, mixed $fieldName) use (&$self, $defaultValues) {
            if ($self->$fieldName === null) {
                $self->$fieldName = $fieldValue;
            }
        });

        return $this;
    }
}
