<?php

namespace App\Models\Traits;

use App\Models\TerminationType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTerminationType
{
    public function terminationType(): BelongsTo
    {
        return $this->belongsTo(TerminationType::class);
    }
}
