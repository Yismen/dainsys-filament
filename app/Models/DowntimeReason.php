<?php

namespace App\Models;

use App\Models\Traits\HasManyDowntimes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DowntimeReason extends \App\Models\BaseModels\AppModel
{
    use HasManyDowntimes;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];
}
