<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class UpdateTicketStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:update-ticket-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of the non-completed tickets!';

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
     *
     * @return int
     */
    public function handle(): int
    {
        $this->tickets = Ticket::incompleted()->with('subject')->get();

        $this->tickets->each(function (Ticket $ticket) {
            $ticket->touch();
        });

        $this->info("Successfully updated {$this->tickets->count()} tickets");

        return 0;
    }
}
