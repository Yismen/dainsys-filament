<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends \App\Models\BaseModels\AppModel
{
    use SoftDeletes;

    protected $fillable = ['name', 'date', 'description'];
}
