<?php

namespace App\Models;

use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoginName extends Model
{
    use BelongsToEmployee;
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = ['login_name', 'employee_id'];
}
