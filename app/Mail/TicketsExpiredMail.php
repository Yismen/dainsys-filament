<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use App\Exports\TicketsExpiredExport;

class TicketsExpiredMail extends Mailable
{
    use Queueable;
    use SerializesModels;
    public string $file_name;
    public $tickets;

    public function __construct($tickets, $file_name = null)
    {
        $this->tickets = $tickets;

        $date = now();
        $this->file_name = $file_name ?: "tickets-expired-{$date->format('Y-m-d')}.xlsx";
    }

    public function build()
    {
        return $this
            ->subject('Tickets Expired Report')
            ->priority(0)
            ->markdown('mail.tickets-expired')
        ;
    }

    public function attachments(): array
    {
        Excel::store(new TicketsExpiredExport($this->tickets), $this->file_name);

        return [
            Attachment::fromStorage($this->file_name),
        ];
    }
}
