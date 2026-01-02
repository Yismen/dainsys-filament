<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Afp extends \App\Models\BaseModels\AppModel
{
    protected $fillable = ['name', 'person_of_contact', 'phone', 'description'];

    public function socialSecurities(): HasMany
    {
        return $this->hasMany(SocialSecurity::class, 'afp_id');
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Employee::class,
            SocialSecurity::class,
            'afp_id', // Foreign key on SocialSecurities table...
            'id', // Foreign key on Employees table...
            'id', // Local key on Afps table...
            'employee_id' // Local key on SocialSecurities table...
        );
    }
}
