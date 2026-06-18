<?php

namespace App\Models;

use App\Enums\EmployeeStatuses;
use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasManyEmployees;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description'])]
class Citizenship extends AppModel
{
    use HasManyEmployees;

    public function hiredEmployees(): HasMany
    {
        return $this->employees()
            ->where('status', EmployeeStatuses::Hired)
            ->orderBy('full_name');
    }
}
