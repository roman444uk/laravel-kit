<?php

namespace LaravelKit\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use LaravelKit\Helpers\LogHelper;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LogsController extends BaseController
{
    use AuthorizesRequests,
        ValidatesRequests;

    public function show(string $type, string $resourceId): BinaryFileResponse|Response
    {
        if (!auth()->check()) {
            throw new NotFoundHttpException();
        }

        $filePath = LogHelper::getResourcePath($resourceId, $type);
        if (!file_exists($filePath)) {
            throw new NotFoundHttpException();
        }

        return response()->download(
            $filePath, $resourceId, [
                'Content-Type' => 'application/json'
            ]
        );
    }
}
