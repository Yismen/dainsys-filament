<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Notifications\Reports\TicketsExpiredReportNotification;
use App\Services\TicketRecipientsService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

#[Description('Send a report with the tickets that are expired!')]
#[Signature('dainsys:send-tickets-expired-report')]
class SendTicketsExpiredReport extends Command
{
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
                'agent',
            ])
            ->get();

        if ($tickets->count() > 0) {
            $recipients = (new TicketRecipientsService)
                ->superAdmins()
                ->supportManagers()
                ->get();
            Notification::send($recipients, new TicketsExpiredReportNotification($tickets));

            $this->info("Report Sent with {$tickets->count()} tickets");
        }

        return 0;
    }
}
