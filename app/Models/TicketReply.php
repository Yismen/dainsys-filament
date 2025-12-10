<?php

namespace App\Models;

use App\Models\Ticket;
use App\Events\TicketReplyCreatedEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketReply extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;

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
}
