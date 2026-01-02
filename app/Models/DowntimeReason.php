<?php

namespace App\Models;

use App\Models\Traits\HasManyDowntimes;
use Illuminate\Database\Eloquent\SoftDeletes;

class DowntimeReason extends \App\Models\BaseModels\AppModel
{
    use HasManyDowntimes;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];
}
