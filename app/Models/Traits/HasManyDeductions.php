<?php

namespace App\Models\Traits;

use App\Models\Deduction;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyDeductions
{
    public function deductions(): HasMany
    {
        return $this->hasMany(Deduction::class);
    }
}
