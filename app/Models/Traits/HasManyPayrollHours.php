<?php

namespace App\Models\Traits;

use App\Models\PayrollHour;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyPayrollHours
{
    public function payrollHours(): HasMany
    {
        return $this->hasMany(PayrollHour::class);
    }
}
