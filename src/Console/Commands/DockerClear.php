<?php

namespace LaravelKit\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class DockerClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:docker-clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Process::run('docker container prune');
        Process::run('docker image prune -a');
        Process::run('docker builder prune');

        // Make sure the containers that may use those volumes are running when you run this.
        // Otherwise, they will be seen as dangling, and therefore deleted.
        Process::run('docker volume ls -qf dangling=true | xargs -r docker volume rm');
    }
}
