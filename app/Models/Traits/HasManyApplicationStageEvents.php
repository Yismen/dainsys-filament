<?php

namespace App\Models\Traits;

use App\Models\ApplicationStageEvent;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyApplicationStageEvents
{
    public function applicationStageEvents(): HasMany
    {
        return $this->hasMany(ApplicationStageEvent::class);
    }
}
