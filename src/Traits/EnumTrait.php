<?php

namespace LaravelKit\Traits;

use Illuminate\Support\Collection;

trait EnumTrait
{
    /**
     * Return enums collection.
     */
    public static function casesCollection(string $fromCasesMethod = null, array $fromCasesArgs = []): Collection
    {
        return collect($fromCasesMethod ? self::$fromCasesMethod(...$fromCasesArgs) : self::cases());
    }

    /**
     * Return array list of enum values.
     */
    public static function getValues(string $fromCasesMethod = null, array $fromCasesArgs = [], array $exceptValues = []): array
    {
        return collect($fromCasesMethod ? self::$fromCasesMethod(...$fromCasesArgs) : self::cases())
            ->pluck('value')
            ->filter(fn($value) => !in_array($value, $exceptValues))
            ->toArray();
    }

    /**
     * Return list of enum translations appropriate to values.
     */
    public static function getValuesTranslations(string $translation): array
    {
        return collect(self::cases())
            ->pluck('value')
            ->map(function (string $item, int $key) use ($translation) {
                return __($translation . '.' . str_replace('-', '_', $item));
            })->toArray();
    }

    /**
     * Return list of enum value => translation map.
     */
    public static function getTranslationMap(string $translation, string $fromCasesMethod = null): Collection
    {
        return collect($fromCasesMethod ? self::$fromCasesMethod() : self::cases())
            ->keyBy('value')
            ->map(function ($item) use ($translation) {
                return __($translation . '.' . str_replace('-', '_', $item->value));
            });
    }

    /**
     * Return all imploded values of enum list.
     */
    public static function getImplodedValues(string $delimiter = ',', string $fromCasesMethod = null): string
    {
        return implode($delimiter, self::getValues($fromCasesMethod));
    }

    /**
     * Transform array sting forms of enum to object form.
     */
    public static function toCases(array $enums = null): Collection
    {
        $casesMap = collect(self::cases())->keyBy('value');

        return collect($enums)->map(function (string $enum) use ($casesMap) {
            return $casesMap->get($enum);
        });
    }
}
