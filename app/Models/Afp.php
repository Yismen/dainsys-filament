<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasHiredEmployees;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[Fillable(['name', 'person_of_contact', 'phone', 'description'])]
class Afp extends AppModel
{
    use HasHiredEmployees;

    public function socialSecurities(): HasMany
    {
        return $this->hasMany(SocialSecurity::class, 'afp_id');
    }

    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(
            Employee::class,
            SocialSecurity::class,
            'afp_id',
            'id',
            'id',
            'employee_id'
        );
    }
}
