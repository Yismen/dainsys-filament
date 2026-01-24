<?php

namespace App\Models\Traits;

use App\Models\User;
use App\Models\Mailable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait BelongsToManyMailables
{
    public function mailables(): BelongsToMany
    {
        return $this->belongsToMany(Mailable::class);
    }
}
