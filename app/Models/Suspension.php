<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Suspension extends Model
{
    use HasFactory;
    protected $fillable = ['employee_id', 'suspension_type_id', 'starts_at', 'ends_at', 'comments'];
}
