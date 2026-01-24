<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailableUser extends Pivot
{
    protected $table = 'mailable_user';

    public function mailable(): BelongsTo
    {
        return $this->belongsTo(Mailable::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
