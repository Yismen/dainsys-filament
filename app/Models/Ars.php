<?php

namespace App\Models;

use App\Enums\EmployeeStatuses;
use App\Models\BaseModels\AppModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[Fillable(['name', 'person_of_contact', 'phone', 'description'])]
#[Table(name: 'arss')]
class Ars extends AppModel
{
    public function socialSecurities(): HasMany
    {
        return $this->hasMany(SocialSecurity::class, 'ars_id');
    }

    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(
            Employee::class,
            SocialSecurity::class,
            'ars_id',
            'id',
            'id',
            'employee_id'
        );
    }

    public function hiredEmployees(): HasManyThrough
    {
        return $this->employees()
            ->where('employees.status', EmployeeStatuses::Hired)
            ->orderBy('employees.full_name');
    }
}
