<?php

namespace App\Models;

use App\Casts\AsMoney;
use App\Enums\RevenueTypes;
use App\Models\Traits\BelongsToCampaign;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\BelongsToSupervisor;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Production extends \App\Models\BaseModels\AppModel
{
    use BelongsToCampaign;
    use BelongsToEmployee;
    use BelongsToSupervisor;
    use SoftDeletes;

    protected $fillable = [
        'date',
        // 'unique_id',
        'employee_id',
        // 'supervisor_id',
        'campaign_id',
        // 'sph_goal',
        // 'revenue_type',
        // 'revenue_rate',
        'conversions',
        'total_time',
        'production_time',
        'talk_time',
        // 'billable_time',
        // 'revenue',
    ];

    protected $casts = [
        'revenue_type' => RevenueTypes::class,
        'revenue' => AsMoney::class,
        'date' => 'date:Y-m-d',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function (self $production) {
            $production->load('campaign', 'employee');

            $production->updateQuietly([
            ]);
        });

        static::saved(function (self $production) {
            $production->load('campaign', 'employee');
            $changed_keys = \array_keys($production->getChanges());

            $production->supervisor_id = \in_array('employee_id', $changed_keys) ?
                $production->employee?->supervisor?->id :
                $production->supervisor_id;
            $production->revenue_rate = \in_array('campaign_id', $changed_keys) ?
                $production->campaign?->revenue_rate :
                $production->revenue_rate;
            $production->sph_goal = \in_array('campaign_id', $changed_keys) ?
                $production->campaign?->sph_goal :
                $production->sph_goal;
            $production->revenue_type = \in_array('campaign_id', $changed_keys) ?
            $production->campaign?->revenue_type :
                    $production->revenue_type;
            $production->billable_time = $production->calculateBillableHours();
            $production->revenue = $production->calculateRevenue();
            $production->unique_id = implode('_', [
                $production->date->format('Y-m-d'),
                $production->campaign_id,
                $production->employee_id,
            ]);

            $production->converted_to_payroll_at = null;

            $production->saveQuietly();
        });
    }

    public function project(): HasOneThrough
    {
        return $this->hasOneThrough(

            related: \App\Models\Project::class,
            through: \App\Models\Campaign::class,
            firstKey: 'id', // Foreign key on the Through table table...
            secondKey: 'id', // Foreign key on Related table...
            localKey: 'campaign_id', // Local key on this Model table...
            secondLocalKey: 'project_id', // Local key on Through table...
        );
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
