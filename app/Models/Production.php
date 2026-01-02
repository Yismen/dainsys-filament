<?php

namespace App\Models;

use App\Casts\AsMoney;
use App\Enums\RevenueTypes;
use App\Models\Traits\BelongsToCampaign;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\BelongsToSupervisor;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Production extends \App\Models\BaseModels\AppModel
{
    use BelongsToCampaign;
    use BelongsToEmployee;
    use BelongsToSupervisor;
    use SoftDeletes;

    protected $fillable = [
        'unique_id',
        'date',
        'employee_id',
        'supervisor_id',
        'campaign_id',
        'sph_goal',
        'revenue_type',
        'revenue_rate',
        'conversions',
        'total_time',
        'production_time',
        'talk_time',
        'billable_time',
        'revenue',
    ];

    protected $casts = [
        // 'revenue' => RevenueTypes::class,
        'revenue' => AsMoney::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function (self $model) {
            $model->load('campaign', 'employee');

            $model->updateQuietly([
            ]);
        });

        static::saved(function (self $model) {
            $model->load('campaign', 'employee');
            $changed_keys = \array_keys($model->getChanges());

            $model->updateQuietly([
                'supervisor_id' => \in_array('employee_id', $changed_keys) ?
                    $model->employee?->supervisor?->id :
                    $model->supervisor_id,
                'revenue_rate' => \in_array('campaign_id', $changed_keys) ?
                    $model->campaign?->revenue_rate :
                        $model->revenue_rate,
                'sph_goal' => \in_array('campaign_id', $changed_keys) ?
                    $model->campaign?->sph_goal :
                        $model->sph_goal,
                'revenue_type' => \in_array('campaign_id', $changed_keys) ?
                    $model->campaign?->revenue_type :
                        $model->revenue_type,
                'billable_time' => $model->calculateBillableHours(),
                'revenue' => $model->calculateRevenue(),
            ]);
        });
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }

    private function calculateBillableHours(): int
    {
        return RevenueTypes::from($this->campaign->revenue_type->value)
            ->calculateBillableHours($this);
    }

    private function calculateRevenue(): int
    {
        return RevenueTypes::from($this->campaign->revenue_type->value)
            ->calculateRevenue($this);
    }
}
