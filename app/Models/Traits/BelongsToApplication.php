<?php

namespace App\Models\Traits;

use App\Models\Application;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToApplication
{
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
