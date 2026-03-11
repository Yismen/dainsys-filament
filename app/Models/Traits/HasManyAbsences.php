<?php

namespace App\Models\Traits;

use App\Models\Absence;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyAbsences
{
    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }
}
