<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasManyEmployees;

class Citizenship extends AppModel
{
    use HasManyEmployees;

    protected $fillable = ['name', 'description'];
}
