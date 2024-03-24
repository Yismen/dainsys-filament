<?php

namespace App\Models\Traits;

use App\Models\DowntimeReason;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToDowntimeReason
{
    public function downtimeReason(): BelongsTo
    {
        return $this->belongsTo(DowntimeReason::class);
    }
}
