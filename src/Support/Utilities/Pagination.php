<?php

namespace LaravelKit\Support\Utilities;

use Illuminate\Pagination\LengthAwarePaginator;

class Pagination
{
    public static function shownRecords(LengthAwarePaginator $paginator): string
    {
        return __('pagination.shown_from_to_with_total', [
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'total' => $paginator->total(),
        ]);
    }
}
