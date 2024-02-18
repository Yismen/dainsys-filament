<?php

namespace App\Models;

use App\Models\Traits\HasManyEmployees;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, HasManyEmployees;
    protected $fillable = ['name', 'description'];
}
