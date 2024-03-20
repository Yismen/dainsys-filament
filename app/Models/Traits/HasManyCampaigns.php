<?php

namespace App\Models\Traits;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyCampaigns
{
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }
}
