<?php

namespace App\Models\Traits;

use App\Models\Citizenship;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToCitizenship
{
    public function citizenship(): BelongsTo
    {
        return $this->belongsTo(Citizenship::class);
    }
}
