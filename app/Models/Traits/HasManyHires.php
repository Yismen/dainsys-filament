<?php

namespace App\Models\Traits;

use App\Models\Hire;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyHires
{
    public function hires(): HasMany
    {
        return $this->hasMany(Hire::class)
            ->with([
                'site',
                'supervisor',
                'project',
                'position',
            ]);
    }
}
