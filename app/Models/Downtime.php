<?php

namespace App\Models;

use App\Models\Traits\BelongsToCampaign;
use App\Models\Traits\BelongsToDowntimeReason;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\HasManyComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Downtime extends \App\Models\BaseModels\AppModel
{
    use BelongsToCampaign;
    use BelongsToDowntimeReason;
    use BelongsToEmployee;

    /** @use HasFactory<\Database\Factories\DowntimeFactory> */
    use HasManyComments;

    use SoftDeletes;

    protected $fillable = [
        'date',
        'employee_id',
        'campaign_id',
        'downtime_reason_id',
        'time',
        'requester_id',
        'aprover_id',
        'converted_to_payroll_at',
    ];

    protected $casts = [
        'date' => "date:Y-m-d"
    ];

    protected static function booted()
    {
        parent::booted();

        static::saved(function (Downtime $downtime) {
            $downtime->unique_id = join('_', [
                $downtime->date->format('Y-m-d'),
                $downtime->campaign_id,
                $downtime->employee_id,
            ]);

            $downtime->saveQuietly();
        });
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function aprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprover_id');
    }
}
