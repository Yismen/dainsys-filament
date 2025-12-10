<?php

namespace App\Models\Traits;

use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyHires
{
    public function hires(): HasMany
    {
        return $this->hasMany(\App\Models\Hire::class);
    }
}
