<?php

namespace App\Models;

use App\Models\Traits\HasInformation;
use App\Models\Traits\HasManyEmployees;
use App\Traits\Models\InteractsWithModelCaching;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Citizenship extends \App\Models\BaseModels\AppModel
{
    use HasManyEmployees;

    protected $fillable = ['name', 'description'];
}
