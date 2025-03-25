<?php

namespace LaravelKit\Data\Casts;

use Illuminate\Database\Eloquent\Casts\Json;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class ArrayFromJson implements Cast
{
    /**
     * Casts array to appropriate data model.
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context, $class = null): array
    {
        if (is_array($value)) {
            return $value;
        }

        return Json::decode($value);
    }
}
