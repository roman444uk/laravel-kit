<?php

namespace LaravelKit\Services\Responses;

use App\Support\Arr;
use Illuminate\Support\Collection;

abstract class OperationResponse implements OperationResponseInterface
{
    public function __construct(
        protected bool $success = true,
        protected array $data = [],
        protected ?string $message = null,
        protected array $errors = []
    )
    {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function get(string|array $key = null): mixed
    {
        return !$key ? $this->data : Arr::getValue($this->data, $key);
    }

    public function getCollectedData(string|array $key = null): Collection
    {
        return collect($this->get($key));
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function pushData(mixed $value, string|int $key = null): self
    {
        if ($key === null) {
            $this->data[] = $value;
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    public static function success(array $data = []): OperationResponseSuccess
    {
        return new OperationResponseSuccess($data);
    }

    public static function error(string $message = null, array $errors = [], array $data = []): OperationResponseError
    {
        return new OperationResponseError($message, $errors, $data);
    }
}
