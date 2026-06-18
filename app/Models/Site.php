<?php

namespace App\Models;

use App\Enums\EmployeeStatuses;
use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasManyEmployees;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'person_of_contact', 'phone', 'email', 'address', 'geolocation', 'description'])]
class Site extends AppModel
{
    use HasManyEmployees;
    use HasManyHires;
    use SoftDeletes;

    public function hiredEmployees(): HasMany
    {
        return $this->employees()
            ->where('status', EmployeeStatuses::Hired)
            ->orderBy('full_name');
    }
}
