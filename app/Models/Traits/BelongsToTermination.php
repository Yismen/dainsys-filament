<?php

namespace App\Models\Traits;

use App\Models\Termination;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTermination
{
    public function termination(): BelongsTo
    {
        return $this->belongsTo(Termination::class);
    }
}
