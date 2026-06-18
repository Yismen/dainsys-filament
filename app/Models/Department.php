<?php

namespace App\Models;

use App\Enums\EmployeeStatuses;
use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasManyEmployeesThruPositions;
use App\Models\Traits\HasManyPositions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'description'])]
class Department extends AppModel
{
    use HasManyEmployeesThruPositions;
    use HasManyPositions;
    use SoftDeletes;

    public function hiredEmployees(): HasManyThrough
    {
        return $this->employees()
            ->where('employees.status', EmployeeStatuses::Hired)
            ->orderBy('employees.full_name');
    }
}
