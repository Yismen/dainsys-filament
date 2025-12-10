<?php

namespace App\Models\Traits;


use App\Models\Source;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToSource
{
    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }
}
