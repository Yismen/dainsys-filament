<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketDepartment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ticket_prefix', 'description'];
}
