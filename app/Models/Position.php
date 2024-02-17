<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'department_id', 'payment_type_id', 'salary', 'description'];

    public function setSalaryAttribute($salary)
    {
        $this->attributes['salary'] = floor($salary * 100);
    }

    public function getSalaryAttribute($salary)
    {
        return $salary / 100;
    }
}
