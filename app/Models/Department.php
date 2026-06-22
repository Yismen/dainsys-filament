<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasHiredEmployees;
use App\Models\Traits\HasManyEmployeesThruPositions;
use App\Models\Traits\HasManyPositions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'description'])]
class Department extends AppModel
{
    use HasHiredEmployees;
    use HasManyEmployeesThruPositions;
    use HasManyPositions;
    use SoftDeletes;
}
