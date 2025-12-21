<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketCompletedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Ticket $ticket;

    public string $comment;

    public function __construct(Ticket $ticket, string $comment = '')
    {
        $this->ticket = $ticket;
        $this->comment = $comment;
    }
}
