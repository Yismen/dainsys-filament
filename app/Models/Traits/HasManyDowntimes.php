<?php

namespace App\Models\Traits;

use App\Models\Downtime;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyDowntimes
{
    public function downtimes(): HasMany
    {
        return $this->hasMany(Downtime::class);
    }
}
