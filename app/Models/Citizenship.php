<?php

namespace App\Models;

use App\Models\Traits\HasManyEmployees;

class Citizenship extends \App\Models\BaseModels\AppModel
{
    use HasManyEmployees;

    protected $fillable = ['name', 'description'];
}
