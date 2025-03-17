<?php

namespace LaravelKit\Support\Utilities;

class Url
{
    /**
     * Extract and return host from url string.
     */
    public static function getHostFromUrl(string $url): ?string
    {
        $urlInfo = parse_url($url);

        return $urlInfo['host'] ?? null;
    }
}
