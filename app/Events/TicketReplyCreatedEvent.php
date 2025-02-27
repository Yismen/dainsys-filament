<?php

namespace App\Events;


use App\Models\TicketReply;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

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
