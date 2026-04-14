<?php

namespace App\Models;

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

    // public function employees(): HasManyThrough
    // {
    //     return $this->hasManyThrough(
    //         \App\Models\Employee::class,
    //         \App\Models\Hire::class,
    //         'position_id', // Foreign key on hires table to positions
    //         'id',          // Employee id referenced by hires
    //         'id',          // Department id
    //         'employee_id'  // Foreign key on hires table to employees
    //     );
    // }

}
