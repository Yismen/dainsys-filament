<?php

use App\Console\Commands\SyncMailables;
use App\Services\MailingService;

it('runs without error the job is not pushed to the queue', function (): void {
    $command = $this->artisan('dainsys:sync-mailables-table');

    $command->assertExitCode(0);
});

it('sync default models seeds', function (): void {
    $mailables = MailingService::toArray();

    $this->artisan(SyncMailables::class)->execute();

    foreach ($mailables as $name => $description) {
        $this->assertDatabaseHas('mailables', [
            'name' => $name,
            'description' => $description,
        ]);
    }
});
