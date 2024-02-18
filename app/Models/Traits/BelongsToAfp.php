<?php

namespace App\Models\Traits;

use App\Models\Afp;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToAfp
{
    public function afp(): BelongsTo
    {
        return $this->belongsTo(Afp::class);
    }
}
