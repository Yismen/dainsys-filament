<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class NightlyHour extends AppModel
{
    use BelongsToEmployee;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'date',
        'total_hours',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
        ];
    }
}
