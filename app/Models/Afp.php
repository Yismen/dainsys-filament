<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Afp extends AppModel
{
    protected $fillable = ['name', 'person_of_contact', 'phone', 'description'];

    public function socialSecurities(): HasMany
    {
        return $this->hasMany(SocialSecurity::class, 'afp_id');
    }

    public function employees(): HasManyThrough
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
