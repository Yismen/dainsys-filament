<?php

namespace App\Models\Traits;

use App\Models\Payroll;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyPayrolls
{
    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }
}
