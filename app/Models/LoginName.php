<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['login_name', 'employee_id'])]
class LoginName extends AppModel
{
    use BelongsToEmployee;
    use SoftDeletes;
}
