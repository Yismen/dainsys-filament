<?php

namespace App\Notifications;

use App\Models\Evaluation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EvaluationCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Evaluation $evaluation) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'evaluation_id' => $this->evaluation->id,
            'status' => $this->evaluation->status->value,
            'message' => 'A new QA evaluation has been created.',
        ];
    }
}
