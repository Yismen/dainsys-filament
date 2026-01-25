<?php

namespace App\Models;

use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supervisor extends \App\Models\BaseModels\AppModel
{
    use HasManyHires;
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Employee::class,
            \App\Models\Hire::class,
            'supervisor_id', // Foreign key on Hires table...
            'id', // Foreign key on Employees table...
            'id', // Local key on Supervisors table...
            'employee_id' // Local key on Hires table...
        );
    }
}
