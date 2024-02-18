<?php

namespace App\Models\Traits;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToBank
{
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
