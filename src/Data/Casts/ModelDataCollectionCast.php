<?php

namespace App\Data\Casts;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class ModelDataCollectionCast implements Cast
{
    /**
     * Casts array to appropriate data model.
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context, $class = null): Collection
    {
        $withCast = $property->attributes->get(0);

        /** @var Data $dataClass */
        $dataClass = $withCast->arguments[0];

        if ($value instanceof $dataClass) {
            return $value;
        }

        return collect($dataClass::collect($value));
    }
}
