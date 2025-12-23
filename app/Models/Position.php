<?php

namespace App\Models;

use App\Casts\AsMoney;
use App\Models\Traits\BelongsToDepartment;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use BelongsToDepartment;
    use HasFactory;
    use HasManyHires;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = ['name', 'department_id', 'salary_type', 'salary', 'description'];

    public $casts = [
        'salary' => AsMoney::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (Position $position) {
            $position->details = implode(', ', [
                $position->name,
                $position->department->name,
                '$' . $position->salary,
                $position->salary_type,
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
