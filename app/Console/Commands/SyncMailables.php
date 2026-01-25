<?php

namespace App\Console\Commands;

use App\Models\Mailable;
use App\Services\MailingService;
use Illuminate\Console\Command;

class SyncMailables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:sync-mailables-table
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync mailables table with the mailing service definitions';

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
