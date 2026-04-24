<?php

namespace App\Notifications\HRActivity;

use App\Models\HRActivityRequest;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HRActivityRequestCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public HRActivityRequest $request) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('hr_activity.created');
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New HR Activity Request: '.$this->request->activity_type->value)
            ->line("A new HR activity request was created for {$this->request->employee->full_name}.")
            ->line('Type: '.$this->request->activity_type->value);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'HR Activity Request Created',
            'body' => "A new {$this->request->activity_type->value} request was created.",
            'format' => 'filament',
            'duration' => 'persistent',
            'request_id' => $this->request->id,
            'employee_id' => $this->request->employee_id,
            'supervisor_id' => $this->request->supervisor_id,
            'status' => $this->request->status?->value,
            'activity_type' => $this->request->activity_type?->value,
        ];
    }
}
