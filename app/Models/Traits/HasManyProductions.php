<?php

namespace App\Models\Traits;

use App\Models\Production;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyProductions
{
    public function productions(): HasMany
    {
        return $this->hasMany(Production::class);
    }
}
