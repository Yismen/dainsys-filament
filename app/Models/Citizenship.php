<?php

namespace App\Models;

use App\Models\Traits\HasManyEmployees;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Citizenship extends Model
{
    use HasFactory, HasManyEmployees, SoftDeletes;
    protected $fillable = ['name', 'description'];
}
