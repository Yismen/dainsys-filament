<?php

namespace App\Models\Traits;

use App\Models\Suspension;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManySuspensions
{
    public function suspensions(): HasMany
    {
        return $this->hasMany(Suspension::class);
    }
}
