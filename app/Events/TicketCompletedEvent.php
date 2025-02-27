<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

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
