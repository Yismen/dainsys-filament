<?php

namespace App\Notifications\Reports;

use App\Mail\BirthdaysMail;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class BirthdaysReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Collection $birthdays,
        public string $type,
    ) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('reports.birthdays');
    }

    public function toMail(object $notifiable): BirthdaysMail
    {
        return new BirthdaysMail($this->birthdays, $this->type);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => "Birthdays {$this->type}",
            'body' => "There are {$this->birthdays->count()} employees having birthdays {$this->type}.",
            'format' => 'filament',
            'duration' => 'persistent',
            'count' => $this->birthdays->count(),
            'type' => $this->type,
        ];
    }
}
