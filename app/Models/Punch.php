<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Punch extends Model
{
    use HasFactory;
    use BelongsToEmployee;
    use SoftDeletes;

    protected $fillable = ['punch', 'employee_id'];
}
