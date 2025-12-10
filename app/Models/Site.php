<?php

namespace App\Models;

use App\Models\Traits\HasManyHires;
use App\Models\Traits\HasInformation;
use App\Models\Traits\HasManyEmployees;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasInformation;
    use HasManyHires;
    use HasUuids;

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
