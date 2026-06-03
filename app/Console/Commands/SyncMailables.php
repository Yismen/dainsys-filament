<?php

namespace App\Console\Commands;

use App\Models\Mailable;
use App\Services\MailingService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Sync mailables table with the mailing service definitions')]
#[Signature('dainsys:sync-mailables-table
    ')]
class SyncMailables extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mailables = MailingService::toArray();

        foreach ($mailables as $name => $description) {
            Mailable::firstOrCreate([
                'name' => $name,
            ], [
                'description' => $description,
            ]);
        }

        $this->info('A total of '.count($mailables).' mailables synced to the mailables table.');

        return self::SUCCESS;
    }
}
