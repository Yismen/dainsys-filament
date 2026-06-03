<?php

namespace App\Console\Commands;

use App\Jobs\SyncDefaultModelsJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Command description')]
#[Signature('dainsys:sync-default-models-seed')]
class SyncDefaultModelsSeed extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        SyncDefaultModelsJob::dispatch();
    }
}
