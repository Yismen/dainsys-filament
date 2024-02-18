<?php

namespace App\Models\Traits;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

trait HasManyEmployeesThruPositions
{
    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(Employee::class, Position::class);
    }
}
