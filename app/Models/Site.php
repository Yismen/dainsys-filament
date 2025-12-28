<?php

namespace App\Models;

use App\Models\Traits\HasInformation;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends \App\Models\BaseModels\AppModel
{
    use HasInformation;
    use HasManyHires;
    use SoftDeletes;

    protected $fillable = ['name', 'person_of_contact', 'description'];

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
