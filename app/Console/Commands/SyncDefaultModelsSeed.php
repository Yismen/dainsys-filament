<?php

namespace App\Console\Commands;

use App\Jobs\SyncDefaultModelsJob;
use App\Models\Source;
use App\Models\SuspensionType;
use Illuminate\Console\Command;

class SyncDefaultModelsSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:sync-default-models-seed';

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
        SyncDefaultModelsJob::dispatch();
    }
}
