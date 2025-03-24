<?php

namespace LaravelKit\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LogsClearController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:logs-clear {olderThan=90} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears logs files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->clearDirectory('exception');
        $this->clearDirectory('request');
    }

    protected function clearDirectory($type): void
    {
        $olderThan = $this->argument('olderThan');
        $clearAll = $this->option('all');

        collect(glob(public_path('logs/' . $type . '/*')))->each(
            function (string $folderPath) use ($type, $olderThan, $clearAll) {
                $dateRaw = pathinfo($folderPath, PATHINFO_FILENAME);
                $date = Carbon::createFromFormat('Y-m-d', $dateRaw);

                if ($date->diffInDays(Carbon::now()) > $olderThan || $clearAll) {
                    File::deleteDirectory(public_path('logs/' . $type . '/' . $dateRaw));
                }
            }
        );
    }
}
