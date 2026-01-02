<?php

namespace App\Models;

use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;

class Universal extends \App\Models\BaseModels\AppModel
{
    use BelongsToEmployee;
    use SoftDeletes;

    protected $fillable = ['employee_id', 'date_since'];
}
