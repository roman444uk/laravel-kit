<?php

namespace LaravelKit\Data\Concerns;

trait FilterableData
{
    public function pageSize(int $pageSize): self
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    public function sort(string $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function desc(string $desc): self
    {
        $this->desc = $desc;

        return $this;
    }
}
