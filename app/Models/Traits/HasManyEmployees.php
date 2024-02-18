<?php

namespace App\Models\Traits;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyEmployees
{
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
