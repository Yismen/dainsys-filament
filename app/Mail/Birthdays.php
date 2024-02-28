<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Services\MailingService;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Birthdays extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public Collection $birthdays;

    public string $type;

    public function __construct(Collection $birthdays, string $type)
    {
        $this->birthdays = $birthdays;
        $this->type = $type;
    }

    public function build()
    {
        return $this
            ->subject("Birthdays {$this->type}")
            ->to(MailingService::subscribers($this))
            ->markdown('mail.birthdays');
    }
}
