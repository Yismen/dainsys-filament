<?php

namespace App\Models\Traits;

use App\Models\Incentive;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyIncentives
{
    public function incentives(): HasMany
    {
        return $this->hasMany(Incentive::class);
    }
}
