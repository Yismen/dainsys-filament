<?php

namespace App\Models;

use App\Events\TicketReplyCreatedEvent;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketReply extends Model
{
    use HasFactory;
    use HasUuids;
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
}
