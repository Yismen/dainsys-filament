<?php

namespace App\Models\Traits;

use App\Models\Hire;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyHires
{
    public function hires(): HasMany
    {
        return $this->hasMany(Hire::class);
    }

    public function hiresWithRelations(): HasMany
    {
        return $this->hires()->with([
            'site',
            'supervisor',
            'project',
            'position',
        ]);
    }
}
