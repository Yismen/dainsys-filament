<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;

class Ars extends AppModel
{
    protected $fillable = ['name', 'person_of_contact', 'phone', 'description'];

    protected $table = 'arss';

    public function socialSecurities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SocialSecurity::class, 'ars_id');
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
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
