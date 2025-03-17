<?php

namespace LaravelKit\Services\Responses;

class OperationResponseBulkCreateUpdate extends OperationResponse
{
    protected int $countProcessed = 0;

    protected int $countCreated = 0;

    protected int $countUpdated = 0;

    public function created(int $count = 1): void
    {
        $this->countProcessed += $count;
        $this->countCreated += $count;
    }

    public function updated(int $count = 1): void
    {
        $this->countProcessed += $count;
        $this->countUpdated += $count;
    }

    public function getProcessedCount(): int
    {
        return $this->countProcessed;
    }

    public function getCreatedCount(): int
    {
        return $this->countCreated;
    }

    public function getUpdatedCount(): int
    {
        return $this->countUpdated;
    }

    public function getMessage(): string
    {
        return __('common.operation.created_updated_count', [
            'processed' => $this->countProcessed,
            'created' => $this->countCreated,
            'updated' => $this->countUpdated,
        ]);
    }
}
