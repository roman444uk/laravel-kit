<?php

namespace LaravelKit\Traits\Controllers;

use Illuminate\Http\Request;

trait Paginatable
{
    /**
     * Sets page size for current resource.
     */
    protected function getPageSize(string $key = null, Request $request = null): int
    {
        if ($request) {
            $this->setPageSize($request->get('page-size'), $key);
        }

        return min(
            request()->session()->get($this->getKey($key ?? $this->pageSizeKey ?? null)) ?: $this->pageSizeDefault ?? 20,
            $this->pageSizeLimit ?? 1000
        );
    }

    protected function setPageSize(int $size = null, string $key = null): void
    {
        request()->session()->put($this->getKey($key ?? $this->pageSizeKey ?? null), $size ?: $this->getPageSize());
    }

    protected function getKey(string $key = null): string
    {
        return 'page-size' . ($key ? '-' . $key : '');
    }
}