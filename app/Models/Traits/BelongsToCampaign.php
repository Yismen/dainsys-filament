<?php

namespace App\Models\Traits;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToCampaign
{
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
