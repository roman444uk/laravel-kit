<?php

namespace LaravelKit\Support;

use ArrayAccess;

class Arr extends \Illuminate\Support\Arr
{
    public static function getValue(array $array, array|string $key, mixed $default = null)
    {
        if ($key instanceof \Closure) {
            return $key($array, $default);
        }

        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                if (!static::keyExists($keyPart, $array)) {
                    return $default;
                }
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }

        if (is_object($array) && property_exists($array, $key)) {
            return $array->$key;
        }

        if (static::keyExists($key, $array)) {
            return $array[$key];
        }

        if ($key && ($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (static::keyExists($key, $array)) {
            return $array[$key];
        }
//        if (is_object($array)) {
//            // this is expected to fail if the property does not exist, or __get() is not implemented
//            // it is not reliably possible to check whether a property is accessible beforehand
//            try {
//                return $array->$key;
//            } catch (\Exception $e) {
//                if ($array instanceof ArrayAccess) {
//                    return $default;
//                }
//                throw $e;
//            }
//        }

        return $default;
    }

    public static function keyExists($key, $array, $caseSensitive = true): bool
    {
        if ($caseSensitive) {
            // Function `isset` checks key faster but skips `null`, `array_key_exists` handles this case
            // https://www.php.net/manual/en/function.array-key-exists.php#107786
            if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
                return true;
            }
            // Cannot use `array_has_key` on Objects for PHP 7.4+, therefore we need to check using [[ArrayAccess::offsetExists()]]
            return $array instanceof ArrayAccess && $array->offsetExists($key);
        }

        /*if ($array instanceof ArrayAccess) {
            throw new InvalidArgumentException('Second parameter($array) cannot be ArrayAccess in case insensitive mode');
        }*/

        foreach (array_keys($array) as $k) {
            if (strcasecmp($key, $k) === 0) {
                return true;
            }
        }

        return false;
    }

    public static function setValue(&$array, $path, $value)
    {
        if ($path === null) {
            $array = $value;
            return;
        }

        $keys = is_array($path) ? $path : explode('.', $path);

        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key])) {
                $array[$key] = [];
            }
            if (!is_array($array[$key])) {
                $array[$key] = [$array[$key]];
            }
            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;
    }

    public static function keysToCamelCaseFromUnderscore(array $array): array
    {
        return self::renameKeys($array, fn ($key) => Str::toCamelCaseFromUnderscore($key));
    }

    public static function renameKeys(array $array, callable $callback): array
    {
        $newArray = [];
        foreach ($array as $key => $value) {
            $newArray[call_user_func($callback, $key)] = is_array($value) ? self::renameKeys($value, $callback) : $value;
        }

        return $newArray;
    }
}
