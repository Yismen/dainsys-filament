<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'employee_id',
    'date',
    'total_hours',
])]
class NightlyHour extends AppModel
{
    use BelongsToEmployee;
    use HasFactory;
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
        ];
    }
}
