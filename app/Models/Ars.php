<?php

namespace App\Models;

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
            'ars_id', // Foreign key on SocialSecurities table...
            'id', // Foreign key on Employees table...
            'id', // Local key on Arss table...
            'employee_id' // Local key on SocialSecurities table...
        );
    }
}
