<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TicketAssignedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }
}
