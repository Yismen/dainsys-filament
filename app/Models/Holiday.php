<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends AppModel
{
    use SoftDeletes;

    protected $fillable = ['name', 'date', 'description'];
}
