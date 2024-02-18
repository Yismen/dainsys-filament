<?php

namespace App\Models\Traits;

use App\Models\Ars;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToArs
{
    public function ars(): BelongsTo
    {
        return $this->belongsTo(Ars::class);
    }
}
