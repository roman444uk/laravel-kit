<?php

namespace LaravelKit\Rules;

use LaravelKit\Helpers\ArrayHelper;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

/**
 * Validates unique values within array income data
 */
class UniqueInArrayRule implements DataAwareRule, ValidationRule
{
    /**
     * Column that should be ignored while searching for duplicate values
     */
    protected mixed $ignoreColumn;

    /**
     * Error message
     */
    protected mixed $message;

    /**
     * Can specified additional search conditions
     */
    protected mixed $whereCallback;

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
     *
     * @param array<string, mixed> $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attributePath, mixed $value, Closure $fail): void
    {
        $attributePath = explode('.', $attributePath);
        array_pop($attributePath);

        $currentRecord = ArrayHelper::getValue($this->data, $attributePath);

        $query = DB::table($this->table)
            ->where($this->column, $value);

        if ($this->ignoreColumn && !empty($currentRecord[$this->ignoreColumn])) {
            $query->whereNot($this->ignoreColumn, $currentRecord[$this->ignoreColumn]);
        }

        if ($this->whereCallback) {
            $query = call_user_func($this->whereCallback, $query);
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
