<?php

namespace LaravelKit\Support;

class Str extends \Illuminate\Support\Str
{
    public static function emptyAsNull(string $str): ?string
    {
        return strlen($str) ? $str : null;
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

    /**
     * Converts camel case notation string to string with specific delimiter.
     */
    public static function fromCamelCase(string $string, string $delimiter = '-'): string
    {
        $regex = '/(?#! splitCamelCase Rev:20140412)
    # Split camelCase "words". Two global alternatives. Either g1of2:
      (?<=[a-z])      # Position is after a lowercase,
      (?=[A-Z])       # and before an uppercase letter.
    | (?<=[A-Z])      # Or g2of2; Position is after uppercase,
      (?=[A-Z][a-z])  # and before upper-then-lower case.
    /x';

        $words = preg_split($regex, $string);
        foreach ($words as $index => $word) {
            $words[$index] = strtolower($word);
        }

        return implode($delimiter, $words);
    }

    /**
     * Converts string with delimiter to camel case notation.
     */
    public static function toCamelCase(string $string, string $delimiter = '-'): string
    {
        return collect(explode($delimiter, $string))->map(function (string $value, int $index) {
            return $index > 0 ? ucfirst($value) : $value;
        })->implode('');
    }

    public static function toCamelCaseFromUnderscore(string $string): string
    {
        return self::tocamelCase($string, '_');
    }

    public static function replaceUrlsWithLinks(string $string, array $options = [], callable $callback = null): string
    {
        $rexProtocol = '(https?://)?';
        $rexDomain = '((?:[-a-zA-Z0-9]{1,63}\.)+[-a-zA-Z0-9]{2,63}|(?:[0-9]{1,3}\.){3}[0-9]{1,3})';
        $rexPort = '(:[0-9]{1,5})?';
        $rexPath = '(/[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]*?)?';
        $rexQuery = '(\?[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
        $rexFragment = '(#[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';

        $callback = $callback ?? function (
            string $fullUrl, string $protocol, string $domain, string $port, string $path, string $query, string $fragment, $options
        ) {
            $fullUrl = $protocol ? $fullUrl : 'http://' . $fullUrl;

            return '<a href="' . $fullUrl . '" target="' . ($options['target'] ?? '_blank') . '">' . $fullUrl . '</a>';
        };

        return preg_replace_callback(
            "&\\b$rexProtocol$rexDomain$rexPort$rexPath$rexQuery$rexFragment(?=[?.!,;:\"]?(\s|$))&",
            function ($match) use ($callback, $options) {
                return call_user_func($callback, $match[0], $match[1], $match[2], $match[3], $match[4], $match[5], $match[6], $options);
            },
            htmlspecialchars($string)
        );
    }

}
