<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoginName extends AppModel
{
    use BelongsToEmployee;
    use SoftDeletes;

    protected $fillable = ['login_name', 'employee_id'];
}
