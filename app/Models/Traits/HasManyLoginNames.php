<?php

namespace App\Models\Traits;

use App\Models\LoginName;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyLoginNames
{
    public function loginNames(): HasMany
    {
        return $this->hasMany(LoginName::class);
    }
}
