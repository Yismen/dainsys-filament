<?php

namespace App\Console\Commands;

use App\Mail\TicketsExpiredMail;
use App\Models\Ticket;
use App\Services\TicketRecipientsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTicketsExpiredReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:send-tickets-expired-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a report with the tickets that are expired!';

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
        $tickets = Ticket::query()
            ->incompleted()
            ->expired()
            ->orderBy('expected_at', 'ASC')
            ->with([
                'owner',
                'operator',
            ])
            ->get();

        if ($tickets->count() > 0) {
            Mail::to(
                (new TicketRecipientsService)
                    ->superAdmins()
                    ->ticketAdmins()
                    ->get()
            )
                ->send(new TicketsExpiredMail($tickets));

            $this->info("Report Sent with {$tickets->count()} tickets");
        }

        return 0;
    }
}
