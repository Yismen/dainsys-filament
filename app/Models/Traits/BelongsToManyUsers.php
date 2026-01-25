<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait BelongsToManyUsers
{
    public function mailables(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
