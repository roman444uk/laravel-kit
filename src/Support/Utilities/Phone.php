<?php

namespace LaravelKit\Support\Utilities;

class Phone
{
    public static function toNumericFromMask(string|null $phone): string
    {
        return substr(self::toNumeric($phone), 1);
    }

    public static function toNumeric(string|null $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    public static function toFormat(string $phone): string
    {
        $phone = self::toRawFormat($phone);

        return '+7 (' . substr($phone, 0, 3) . ') ' . implode('-', [
                substr($phone, 3, 3), substr($phone, 6, 2), substr($phone, 8, 2)
            ]);
    }

    /**
     * Extracts raw digits from phone number.
     */
    public static function toRawFormat(string|null $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        return substr(preg_replace('/[^0-9]/', '', $phone), -10);
    }
}
