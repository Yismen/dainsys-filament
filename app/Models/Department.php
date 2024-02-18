<?php

namespace App\Models;

use App\Models\Traits\HasManyPositions;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasManyEmployeesThruPositions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory, HasManyEmployeesThruPositions, HasManyPositions;
    protected $fillable = ['name', 'description'];
}
