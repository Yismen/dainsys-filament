<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends \App\Models\BaseModels\AppModel
{
    use SoftDeletes;

    protected $fillable = ['name', 'date', 'description'];
}
