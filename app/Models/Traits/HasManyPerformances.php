<?php

namespace App\Models\Traits;

use App\Models\Performance;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyPerformances
{
    public function performances(): HasMany
    {
        return $this->hasMany(Performance::class);
    }
}
