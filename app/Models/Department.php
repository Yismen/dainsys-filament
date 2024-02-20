<?php

namespace App\Models;

use App\Models\Traits\HasManyPositions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HasManyEmployeesThruPositions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory, HasManyEmployeesThruPositions, HasManyPositions, SoftDeletes;
    protected $fillable = ['name', 'description'];
}
