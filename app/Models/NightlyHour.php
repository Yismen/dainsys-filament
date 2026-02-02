<?php

namespace App\Models;

use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class NightlyHour extends \App\Models\BaseModels\AppModel
{
    use BelongsToEmployee;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'date',
        'total_hours',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];
}
