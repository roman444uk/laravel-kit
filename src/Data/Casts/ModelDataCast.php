<?php

namespace App\Data\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class ModelDataCast implements Cast
{
    /**
     * Casts array to appropriate data model.
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context, $class = null): Data
    {
        $withCast = $property->attributes->get(0);

        /** @var Data $dataClass */
        $dataClass = $withCast->arguments[0];

        if ($value instanceof $dataClass) {
            return $value;
        }

        return $dataClass::from($value);
    }
}
