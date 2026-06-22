<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasHiredEmployees;
use App\Models\Traits\HasManyEmployees;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description'])]
class Citizenship extends AppModel
{
    use HasHiredEmployees;
    use HasManyEmployees;
}
