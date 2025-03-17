<?php

namespace LaravelKit\Traits\Db;

trait ActionLogTrait
{
    /**
     * Returns model class entity name
     *
     * @return string
     */
    public static function entityName(): string
    {
        return strtolower(substr(self::class, strrpos(self::class, '\\') + 1));
    }
}
