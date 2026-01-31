<?php

namespace App\Models\Traits;

use App\Models\Supervisor;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasOneSupervisor
{
    public function supervisor(): HasOne
    {
        return $this->hasOne(Supervisor::class);
    }
}
