<?php

namespace App\Models;

use App\Models\Traits\HasManyDowntimes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DowntimeReason extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasManyDowntimes;
    use HasUuids;

    protected $fillable = ['name', 'description'];
}
