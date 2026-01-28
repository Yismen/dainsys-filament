<?php

namespace App\Models;

use App\Casts\AsMoney;
use App\Enums\SalaryTypes;
use App\Models\Traits\BelongsToDepartment;
use App\Models\Traits\HasManyEmployees;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends \App\Models\BaseModels\AppModel
{
    use BelongsToDepartment;
    use HasManyEmployees;
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
}
