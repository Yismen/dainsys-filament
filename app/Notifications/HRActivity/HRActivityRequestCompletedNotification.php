<?php

namespace App\Notifications\HRActivity;

use App\Models\HRActivityRequest;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HRActivityRequestCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public HRActivityRequest $request,
        public string $comment
    ) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('hr_activity.completed');
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('HR Activity Request Completed: '.$this->request->activity_type->value)
            ->line("The HR activity request for {$this->request->employee->full_name} was completed.")
            ->line("Comment: {$this->comment}");
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'HR Activity Request Completed',
            'body' => "The {$this->request->activity_type->value} request was completed.",
            'format' => 'filament',
            'duration' => 'persistent',
            'request_id' => $this->request->id,
            'employee_id' => $this->request->employee_id,
            'supervisor_id' => $this->request->supervisor_id,
            'status' => $this->request->status?->value,
            'activity_type' => $this->request->activity_type?->value,
            'comment' => $this->comment,
        ];
    }
}
