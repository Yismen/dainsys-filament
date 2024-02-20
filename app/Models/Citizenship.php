<?php

namespace App\Models;

use App\Models\Traits\HasInformation;
use App\Models\Traits\HasManyEmployees;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Citizenship extends Model
{
    use HasFactory, HasManyEmployees, HasInformation, SoftDeletes;
    protected $fillable = ['name', 'description'];
}
