<?php

namespace App\Models;

use App\Models\Supervisor;
use App\Enums\RevenueTypes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCampaign;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\BelongsToDowntimeReason;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Performance extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToEmployee;
    use BelongsToCampaign;
    use BelongsToDowntimeReason;

    protected $fillable = [
        'file', 'date', 'employee_id', 'campaign_id', 'campaign_goal', 'login_time', 'production_time', 'talk_time', 'billable_time', 'attempts', 'contacts', 'successes', 'upsales', 'revenue', 'downtime_reason_id', 'reporter_id',
    ];

    protected $casts = [
        // 'revenue' => RevenueTypes::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (self $model) {
            $model->updateQuietly([
                'revenue' => RevenueTypes::from($model->campaign->revenue_type->value)->performanceRevenue($model),
                'billable_time' => RevenueTypes::from($model->campaign->revenue_type->value)->performanceBillableTime($model),
            ]);
        });
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }
}
