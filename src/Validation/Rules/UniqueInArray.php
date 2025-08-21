<?php

namespace LaravelKit\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use LaravelKit\Support\Arr;

/**
 * Validates unique values within array income data
 */
class UniqueInArray implements DataAwareRule, ValidationRule
{
    /**
     * Column that should be ignored while searching for duplicate values
     */
    protected mixed $ignoreColumn = null;

    /**
     * Callback defines whether field validation of current row  should be skipped
     */
    protected mixed $skipValidationIfCallback = null;

    /**
     * Error message
     */
    protected mixed $message = null;

    /**
     * Callback where additional search conditions could be specified
     */
    protected mixed $whereCallback = null;

    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    public function __construct(
        protected string $table,
        protected string $column
    )
    {
    }

    /**
     * Set the data under validation.
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $attributePath = explode('.', $attribute);
        array_pop($attributePath);

        $currentRow = Arr::getValue($this->data, $attributePath);

        if ($this->skipValidationIfCallback && call_user_func($this->skipValidationIfCallback, $currentRow)) {
            return;
        }

        $query = DB::table($this->table)
            ->where($this->column, $value);

        if ($this->ignoreColumn && !empty($currentRow[$this->ignoreColumn])) {
            $query->whereNot($this->table . '.' . $this->ignoreColumn, $currentRow[$this->ignoreColumn]);
        }

        if ($this->whereCallback) {
            $query = call_user_func($this->whereCallback, $query, $currentRow);
        }

        if ($query->get()->count()) {
            if (is_callable($this->message)) {
                call_user_func($this->message, $fail, $value);
            }
        }

    }

    public function ignoreColumn(mixed $column): static
    {
        $this->ignoreColumn = $column;

        return $this;
    }

    public function skipValidationIf(callable $callback): static
    {
        $this->skipValidationIfCallback = $callback;

        return $this;
    }

    public function message(mixed $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function where(callable $callback): static
    {
        $this->whereCallback = $callback;

        return $this;
    }
}
