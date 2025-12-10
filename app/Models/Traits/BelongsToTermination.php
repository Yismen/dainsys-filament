<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTermination
{
    public function termination(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Termination::class);
    }
}
