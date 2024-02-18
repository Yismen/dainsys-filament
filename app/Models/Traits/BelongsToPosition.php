<?php

namespace App\Models\Traits;

use App\Models\Position;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToPosition
{
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
