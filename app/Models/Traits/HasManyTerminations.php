<?php

namespace App\Models\Traits;

use App\Models\Termination;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyTerminations
{
    public function terminations(): HasMany
    {
        return $this->hasMany(Termination::class);
    }
}
