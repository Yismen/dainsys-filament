<?php

namespace App\Models\Traits;

use App\Models\Applicant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToApplicant
{
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }
}
