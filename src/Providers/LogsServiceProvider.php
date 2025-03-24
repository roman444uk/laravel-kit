<?php

namespace LaravelKit\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelKit\Console\Commands\ElasticLogsClearController;
use LaravelKit\Console\Commands\LogsClearController;

class LogsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            LogsClearController::class
        ]);
    }
}
