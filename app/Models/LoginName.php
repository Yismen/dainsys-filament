<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginName extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToEmployee;
    use HasUuids;

    protected $fillable = ['login_name', 'employee_id'];
}
