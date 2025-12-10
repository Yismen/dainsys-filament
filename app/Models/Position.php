<?php

namespace App\Models;

use App\Casts\AsMoney;
use App\Models\Traits\HasManyHires;
use App\Models\Traits\HasManyEmployees;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToDepartment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToDepartment;
    use HasManyHires;
    use HasUuids;

    protected $fillable = ['name', 'department_id', 'salary_type', 'salary', 'description'];

    protected $appends = [
        'details'
    ];

    public $casts = [
        'salary' => AsMoney::class
    ];

    public function details(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => join(", ", [
                $this->name,
                $this->department->name,
                "$" . $this->salary,
                $this->salary_type
            ])
        );
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Employee::class,
            \App\Models\Hire::class,
            'position_id', // Foreign key on Hires table...
            'id', // Foreign key on Employees table...
            'id', // Local key on Positions table...
            'employee_id' // Local key on Hires table...
        );
    }
}
