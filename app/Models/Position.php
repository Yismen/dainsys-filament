<?php

namespace App\Models;

use App\Models\Traits\HasManyEmployees;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToDepartment;
use App\Models\Traits\BelongsToPaymentType;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory, SoftDeletes, HasManyEmployees, BelongsToDepartment, BelongsToPaymentType;

    protected $fillable = ['name', 'department_id', 'payment_type_id', 'salary', 'description'];

    protected $appends = [
        'details'
    ];

    public function setSalaryAttribute($salary)
    {
        $this->attributes['salary'] = floor($salary * 100);
    }

    public function getSalaryAttribute($salary)
    {
        return $salary / 100;
    }

    public function details(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => join(", ", [
                $this->name,
                $this->department->name,
                "$" . $this->salary,
                $this->paymentType->name
            ])
        );
    }
}
