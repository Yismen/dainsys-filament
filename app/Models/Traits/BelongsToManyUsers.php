<?php

namespace App\Models\Traits;

use App\Models\User;
use App\Models\Mailable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait BelongsToManyUsers
{
    public function mailables(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
