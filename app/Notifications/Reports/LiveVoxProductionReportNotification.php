<?php

namespace App\Notifications\Reports;

use App\Mail\LiveVoxProductionReportMail;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LiveVoxProductionReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $title,
        public array $attachmentFiles,
    ) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('reports.livevox_production');
    }

    public function toMail(object $notifiable): LiveVoxProductionReportMail
    {
        return new LiveVoxProductionReportMail(
            title: $this->title,
            attachment_files: $this->attachmentFiles,
        );
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => 'LiveVox publishing production report is ready.',
            'format' => 'filament',
            'duration' => 'persistent',
        ];
    }
}
