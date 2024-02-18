<?php

namespace App\Models\Traits;

use App\Models\TerminationReason;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTerminationReason
{
    public function terminationReason(): BelongsTo
    {
        return $this->belongsTo(TerminationReason::class);
    }
}
