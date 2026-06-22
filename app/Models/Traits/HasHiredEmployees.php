<?php

namespace App\Models\Traits;

use App\Enums\EmployeeStatuses;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

trait HasHiredEmployees
{
    public function hiredEmployees(): HasMany|HasManyThrough
    {
        return $this->employees()
            ->where('employees.status', EmployeeStatuses::Hired)
            ->orderBy('employees.full_name');
    }
}
