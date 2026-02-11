<?php

namespace App\Models;

use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\BelongsToProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Incentive extends \App\Models\BaseModels\AppModel
{
    use BelongsToEmployee;
    use BelongsToProject;

    /** @use HasFactory<\Database\Factories\IncentiveFactory> */
    use HasFactory;

    protected $fillable = [
        'payable_date',
        'employee_id',
        'project_id',
        'total_production_hours',
        'total_sales',
        'amount',
        'notes',
    ];

    protected function payableDate(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(get: function ($value) {
            return Carbon::parse($value)->format('Y-m-d');
        });
    }

    protected function casts(): array
    {
        return [
            'payable_date' => 'date:Y-m-d',
            'total_production_hours' => 'decimal:2',
            'total_sales' => 'decimal:2',
            'amount' => 'decimal:2',
        ];
    }
}
