<?php

namespace App\Models;

use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoginName extends \App\Models\BaseModels\AppModel
{
    use BelongsToEmployee;
    use SoftDeletes;

    protected $fillable = ['login_name', 'employee_id'];
}
