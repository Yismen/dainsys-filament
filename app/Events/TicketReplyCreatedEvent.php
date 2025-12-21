<?php

namespace App\Events;

use App\Models\TicketReply;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketReplyCreatedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public TicketReply $reply;

    public function __construct(TicketReply $reply)
    {
        $this->reply = $reply;
    }
}
