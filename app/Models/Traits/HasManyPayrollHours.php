<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyPayrollHours
{
    public function payrollHours(): HasMany
    {
        return $this->hasMany(\App\Models\PayrollHour::class);
    }
}
