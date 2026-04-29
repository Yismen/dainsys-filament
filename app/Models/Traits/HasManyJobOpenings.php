<?php

namespace App\Models\Traits;

use App\Models\JobOpening;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyJobOpenings
{
    public function jobOpenings(): HasMany
    {
        return $this->hasMany(JobOpening::class);
    }
}
