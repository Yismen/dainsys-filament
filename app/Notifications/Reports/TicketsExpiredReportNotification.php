<?php

namespace App\Notifications\Reports;

use App\Mail\TicketsExpiredMail;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class TicketsExpiredReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Collection $tickets) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('reports.tickets_expired');
    }

    public function toMail(object $notifiable): TicketsExpiredMail
    {
        return new TicketsExpiredMail($this->tickets);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Tickets Expired Report',
            'body' => "There are {$this->tickets->count()} expired tickets.",
            'format' => 'filament',
            'duration' => 'persistent',
            'count' => $this->tickets->count(),
        ];
    }
}
