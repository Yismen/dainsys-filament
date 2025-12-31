<?php

namespace App\Mail;

use App\Services\MailingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class BirthdaysMail extends Mailable implements ShouldQueue
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
