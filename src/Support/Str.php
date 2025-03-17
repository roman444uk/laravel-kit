<?php

namespace LaravelKit\Support;

class Str extends \Illuminate\Support\Str
{
    public static function emptyAsNull(string $str): ?string
    {
        return strlen($str) ? $str : null;
    }

    /**
     * Converts string with delimiter to camel case notation
     */
    public static function toCamelCase(string $string, string $delimiter = '-'): string
    {
        return collect(explode($delimiter, $string))->map(function (string $value, int $index) {
            return $index > 0 ? ucfirst($value) : $value;
        })->implode('');
    }

    public static function delimiterChange(string $string, string $from, string $to): string
    {
        return str_replace($from, $to, $string);
    }

    public static function delimiterFromDashToUnderscore(string $string): string
    {
        return self::delimiterChange($string, '-', '_');
    }

    public static function delimiterFromUnderscoreToDash(string $string): string
    {
        return self::delimiterChange($string, '_', '-');
    }
}
