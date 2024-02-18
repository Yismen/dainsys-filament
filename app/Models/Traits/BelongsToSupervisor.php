<?php

namespace App\Models\Traits;


use App\Models\Supervisor;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToSupervisor
{
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }
}
