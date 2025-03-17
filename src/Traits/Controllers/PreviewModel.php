<?php

namespace LaravelKit\Traits\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

trait PreviewModel
{
    protected function fillPreviewModelData(Model &$model = null, bool $forget = false): void
    {
        $model?->fill(array_filter($this->getPreviewModelData($model)));

        if ($forget) {
            Cache::delete($this->getPreviewModelCacheKey($model));
        }
    }

    protected function getPreviewModelData(Model $model = null): array
    {
        return Cache::get($this->getPreviewModelCacheKey($model), $model?->toArray() ?? []);
    }

    protected function setPreviewModelData(Request $request, Model $model = null): void
    {
        Cache::put($this->getPreviewModelCacheKey($model), $request->all());
    }

    protected function getPreviewModelCacheKey(Model $model = null): string
    {
        return $this->previewModelCacheKey . '-' . ($model?->id ?? 'new');
    }
}
