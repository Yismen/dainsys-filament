<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToEmployee;
use Database\Factories\DeductionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable([
    'employee_id',
    'payable_date',
    'amount',
    'description',
])]
class Deduction extends AppModel
{
    use BelongsToEmployee;

    /** @use HasFactory<DeductionFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'payable_date' => 'date:Y-m-d',
            'amount' => 'decimal:2',
        ];
    }
}
