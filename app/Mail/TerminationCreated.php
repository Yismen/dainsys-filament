<?php

namespace App\Mail;

use App\Models\Termination;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TerminationCreated extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public Termination $termination;

    public function __construct(Termination $termination)
    {
        $this->termination = $termination;
    }

    public function build()
    {
        return $this
            ->to(MailingService::subscribers($this))
            ->markdown('dainsys::mail.termination-created');
    }
}
