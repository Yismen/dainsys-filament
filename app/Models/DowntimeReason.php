<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasManyDowntimes;
use Illuminate\Database\Eloquent\SoftDeletes;

class DowntimeReason extends AppModel
{
    use HasManyDowntimes;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];
}
