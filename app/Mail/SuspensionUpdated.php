<?php

namespace App\Mail;

use App\Models\Suspension;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Services\MailingService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SuspensionUpdated extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public Suspension $suspension;

    public function __construct(Suspension $suspension)
    {
        $this->suspension = $suspension;
    }

    public function build()
    {
        return $this
            ->to(MailingService::subscribers($this))
            ->markdown('mail.suspension-updated');
    }
}
