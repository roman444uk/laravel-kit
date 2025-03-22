<?php

namespace LaravelKit\Data\Transformers;

use App\Support\Utilities\DateTime;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Transformers\Transformer;

class CarbonToArrayTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): array
    {
        return [
            'date' => DateTime::renderDate($value),
            'datetime' => DateTime::renderDateTime($value),
            'time' => DateTime::renderTime($value),
            'timestamp' => strtotime($value),
        ];
    }
}
