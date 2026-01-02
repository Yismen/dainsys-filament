<?php

namespace App\Models;

use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends \App\Models\BaseModels\AppModel
{
    use HasManyHires;
    use SoftDeletes;

    protected $fillable = ['name', 'person_of_contact', 'phone', 'email', 'address', 'geolocation', 'description'];

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Employee::class,
            \App\Models\Hire::class,
            'site_id', // Foreign key on Hires table...
            'id', // Foreign key on Employees table...
            'id', // Local key on Sites table...
            'employee_id' // Local key on Hires table...
        );
    }
}
