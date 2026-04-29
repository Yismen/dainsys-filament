<?php

namespace App\Models\Traits;

use App\Models\Application;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyApplications
{
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
