<?php

namespace App\Models;

use App\Events\TicketReplyCreatedEvent;
use App\Models\BaseModels\AppModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketReply extends AppModel
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'ticket_id', 'content'];

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
