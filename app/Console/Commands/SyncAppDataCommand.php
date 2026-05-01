<?php

namespace App\Console\Commands;

use App\Jobs\QueueableCommandsHandlerJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Queue;

#[Signature('dainsys:sync-app-data')]
#[Description('Command description')]
class SyncAppDataCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        Queue::push(new QueueableCommandsHandlerJob('dainsys:sync-default-models-seed'));
        Queue::push(new QueueableCommandsHandlerJob('dainsys:sync-roles-for-panels web'));
        Queue::push(new QueueableCommandsHandlerJob('shield:generate --all --panel=* --option=permissions'));

        $this->info('App data synchronization job has been dispatched.');
    }
}
