<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

#[Description('Update the status of the non-completed tickets!')]
#[Signature('dainsys:update-ticket-status')]
class UpdateTicketStatus extends Command
{
    protected Collection $tickets;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->tickets = Ticket::incompleted()->get();

        $this->tickets->each(function (Ticket $ticket): void {
            $ticket->touch();
        });

        $this->info("Successfully updated {$this->tickets->count()} tickets");

        return 0;
    }
}
