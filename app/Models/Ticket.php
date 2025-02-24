<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['owner_id', 'department_id', 'subject', 'description', 'reference', 'images', 'status', 'priority'];
}
