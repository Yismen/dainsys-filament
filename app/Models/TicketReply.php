<?php

namespace App\Models;

use App\Events\TicketReplyCreatedEvent;
use App\Models\BaseModels\AppModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'ticket_id', 'content'])]
class TicketReply extends AppModel
{
    use SoftDeletes;

    protected $dispatchesEvents = [
        'created' => TicketReplyCreatedEvent::class,
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function author(): BelongsTo
    {
        return $this->user();
    }
}
