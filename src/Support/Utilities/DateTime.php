<?php

namespace LaravelKit\Support\Utilities;

use Illuminate\Support\Carbon;

class DateTime
{
    const DATE_TIME_DB_FORMAT = 'Y-m-d H:i:s';
    const DATE_REGEX = '/^[0-9]{2}.[0-9]{2}.[0-9]{4}$/i';
    const TIME_REGEX = '/^[0-9]{1,2}:[0-9]{2}$/i';

    /**
     * Renders common date representation.
     */
    public static function renderDate(string $date = null, string $format = 'd.m.Y'): ?string
    {
        if (!$date || mb_strlen($date) < 10) {
            return null;
        }

        return self::createFrom($date)->format($format);
    }

    public static function renderDateFromCarbon(Carbon $carbon = null, string $format = 'd.m.Y'): ?string
    {
        return $carbon?->format($format);
    }

    /**
     * Renders common datetime representation.
     */
    public static function renderDateTime(string $dateTime = null, string $format = 'd.m.Y H:i'): ?string
    {
        if (!$dateTime || mb_strlen($dateTime) < 19) {
            return null;
        }

        return self::createFrom($dateTime)->setTimezone('Europe/Moscow')->format($format);
    }

    /**
     * Renders common time representation.
     */
    public static function renderTime(string $time = null, string $format = 'H:i'): ?string
    {
        if (!$time || mb_strlen($time) === 10) {
            return null;
        }

        return Carbon::createFromTimeString($time)->format($format);
    }

    public static function createFrom(string $dateTime): Carbon
    {
        return mb_strlen($dateTime) === 10 ? Carbon::createFromDate($dateTime) : Carbon::createFromTimeString($dateTime);
    }

    public static function dateToDbFormat(string $time = null, string $format = 'Y-m-d'): ?string
    {
        if (!$time) {
            return null;
        }

        return date($format, strtotime($time));
    }

    public static function timeToDBFormat(string $time = null): ?string
    {
        if (!$time) {
            return null;
        }

        return Carbon::createFromTimeString($time)->format('H:i:s');
    }

    public static function renderNowToDBFormat(): ?string
    {
        return Carbon::now()->setTimezone('Europe/Moscow')->format(self::DATE_TIME_DB_FORMAT);
    }
}
