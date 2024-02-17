<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Termination extends Model
{
    use HasFactory;
    protected $fillable = ['employee_id', 'date', 'termination_type_id', 'termination_reason_id', 'comments', 'rehireable'];
}
