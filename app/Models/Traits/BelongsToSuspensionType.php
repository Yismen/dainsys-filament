<?php

namespace App\Models\Traits;

use App\Models\SuspensionType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToSuspensionType
{
    public function suspensionType(): BelongsTo
    {
        return $this->belongsTo(SuspensionType::class);
    }
}
