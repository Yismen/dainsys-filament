<?php

namespace App\Notifications;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbsenceReportedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Absence $absence,
        public User $reporter
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $typeLabel = $this->absence->type?->value ?? 'Pending';

        return (new MailMessage)
            ->subject('Absence Report Update')
            ->greeting("Hello {$notifiable->name},")
            ->line("Your absence on {$this->absence->date->format('Y-m-d')} has been reviewed.")
            ->line('**Status:** Reported')
            ->line("**Type:** {$typeLabel}")
            ->line('If you have any questions, please contact HR.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Absence Reported',
            'message' => "Your absence on {$this->absence->date->format('Y-m-d')} has been marked as reported by {$this->reporter->name}.",
            'absence_id' => $this->absence->id,
            'absence_date' => $this->absence->date->format('Y-m-d'),
            'absence_type' => $this->absence->type?->value,
            'reporter_name' => $this->reporter->name,
        ];
    }
}
