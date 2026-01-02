<?php

namespace App\Models;

use App\Casts\AsMoney;
use App\Enums\SalaryTypes;
use App\Models\Traits\BelongsToDepartment;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends \App\Models\BaseModels\AppModel
{
    use BelongsToDepartment;
    use HasManyHires;
    use SoftDeletes;

    protected $fillable = ['name', 'department_id', 'salary_type', 'salary', 'description'];

    public $casts = [
        'salary' => AsMoney::class,
        'salary_type' => SalaryTypes::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (Position $position) {
            $position->details = implode(', ', [
                $position->name,
                $position->department->name,
                '$'.$position->salary,
                $position->salary_type->name,
            ]);

            $position->saveQuietly();
        });
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
