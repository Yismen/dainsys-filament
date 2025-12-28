<?php

namespace App\Models;

use App\Casts\AsMoney;
use App\Enums\SalaryTypes;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToDepartment;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Models\InteractsWithModelCaching;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use BelongsToDepartment;
    use HasFactory;
    use HasManyHires;
    use HasUuids;
    use SoftDeletes;
    use InteractsWithModelCaching;

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
                '$' . $position->salary,
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
