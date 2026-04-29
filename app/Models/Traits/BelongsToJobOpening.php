<?php

namespace App\Models\Traits;

use App\Models\JobOpening;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToJobOpening
{
    public function jobOpening(): BelongsTo
    {
        return $this->belongsTo(JobOpening::class);
    }
}
