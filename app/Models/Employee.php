<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;
    protected $casts = [
        'date_of_birth' => 'datetime:Y-m-d',
        'hired_at' => 'datetime:Y-m-d',
    ];

    protected $fillable = ['first_name', 'second_first_name', 'last_name', 'second_last_name', 'full_name', 'personal_id', 'hired_at', 'date_of_birth', 'cellphone', 'status', 'marriage', 'gender', 'kids', 'site_id', 'project_id', 'position_id', 'citizenship_id', 'supervisor_id', 'afp_id', 'ars_id'];
}
