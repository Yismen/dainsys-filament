<?php

namespace App\Models\Traits;

use App\Models\Position;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyPositions
{
    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }
}
