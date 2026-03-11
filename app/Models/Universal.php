<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;

class Universal extends AppModel
{
    use BelongsToEmployee;
    use SoftDeletes;

    protected $fillable = ['employee_id', 'date_since'];
}
