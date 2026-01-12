<?php

namespace App\Mail;

use App\Exports\TicketsExpiredExport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

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
            ->markdown('mail.support.tickets-expired');
    }

    public function attachments(): array
    {
        Excel::store(export: new TicketsExpiredExport($this->tickets), filePath: $this->file_name);

        return [
            Attachment::fromStorage($this->file_name),
        ];
    }
}
