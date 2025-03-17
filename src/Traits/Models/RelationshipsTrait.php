<?php

namespace LaravelKit\Traits\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

trait RelationshipsTrait
{
    /**
     * Returns model's relationship only if it's been loaded or default value instead.
     */
    public function whenLoaded(string $relationName, mixed $callbackOrDefault = null, mixed $default = null): mixed
    {
        $defaultValue = null;

        if (
            $this->$relationName() instanceof HasOne
            || $this->$relationName() instanceof BelongsTo
            || $this->$relationName() instanceof HasOneThrough
        ) {
            $defaultValue = null;
        }

        if (
            $this->$relationName() instanceof HasMany
            || $this->$relationName() instanceof BelongsToMany
            || $this->$relationName() instanceof HasManyThrough
        ) {
            $defaultValue = new Collection();
        }

        /** In case if callable has been passed */
        if (is_callable($callbackOrDefault)) {
            return $this->relationLoaded($relationName) && $this->$relationName
                ? call_user_func($callbackOrDefault, $this)
                : ($default ?? $defaultValue);
        }

        /** In case if callable hasn't been passed */
        if ($this->relationLoaded($relationName)) {
            return $this->$relationName;
        }

        return $callbackOrDefault ?? $defaultValue;
    }

    public function loadIfNot(string $relationName): void
    {
        if (!$this->relationLoaded($relationName)) {
            $this->load($relationName);
        }
    }
}
