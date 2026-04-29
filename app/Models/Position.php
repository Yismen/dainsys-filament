<?php

namespace App\Models;

use App\Casts\AsMoney;
use App\Enums\SalaryTypes;
use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToDepartment;
use App\Models\Traits\HasManyEmployees;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'department_id', 'salary_type', 'salary', 'description'])]
class Position extends AppModel
{
    use BelongsToDepartment;
    use HasManyEmployees;
    use HasManyHires;
    use SoftDeletes;

    public $casts = [
        'salary' => AsMoney::class,
        'salary_type' => SalaryTypes::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (Position $position): void {
            $position->details = implode(', ', [
                $position->name,
                $position->department->name,
                '$'.$position->salary,
                $position->salary_type->name,
            ]);

            $position->saveQuietly();
        });
    }

    protected function hourlyRate(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->attributes['salary_type'] === SalaryTypes::Salary->value ?
                    $this->salary / 190.64 : // Assuming 8 working hours in a day and 23.83 working days in a month
                    $this->salary;
            },
        );
    }
}
