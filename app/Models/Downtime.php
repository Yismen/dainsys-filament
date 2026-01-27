<?php

namespace App\Models;

use App\Enums\DowntimeStatuses;
use App\Enums\RevenueTypes;
use App\Exceptions\InvalidDowntimeCampaign;
use App\Models\Traits\BelongsToCampaign;
use App\Models\Traits\BelongsToDowntimeReason;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\HasManyComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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
        'total_time',
        'status',
        // 'requester_id',
        // 'aprover_id',
        'converted_to_payroll_at',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'status' => DowntimeStatuses::class,
    ];

    protected static function booted()
    {
        parent::booted();

        static::saving(function (Downtime $downtime) {
            if ($downtime->campaign->revenue_type !== RevenueTypes::Downtime) {
                throw new InvalidDowntimeCampaign;
            }
        });

        static::saved(function (Downtime $downtime) {
            $downtime->unique_id = implode('_', [
                $downtime->date->format('Y-m-d'),
                $downtime->campaign_id,
                $downtime->employee_id,
            ]);

            $downtime->requester_id = auth()->user()?->id;

            $downtime->saveQuietly();

            // Track initial request as a comment only if none exists
            if ($downtime->wasRecentlyCreated && ! $downtime->comments()->exists()) {
                \App\Models\Comment::query()->forceCreate([
                    'text' => 'Requested by: '.(auth()->user()?->name ?? 'system'),
                    'commentable_id' => $downtime->id,
                    'commentable_type' => self::class,
                ]);
            }
        });

        static::softDeleted(function (Downtime $downtime) {
            $downtime->unAprove();
        });

        static::deleted(function (Downtime $downtime) {
            $downtime->unAprove();
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

    public function aprove()
    {
        DB::transaction(function () {
            $this->aprover_id = auth()->user()->id;
            $this->status = DowntimeStatuses::Approved;

            $this->saveQuietly();

            Production::updateOrCreate(
                [
                    'date' => $this->date,
                    'campaign_id' => $this->campaign_id,
                    'employee_id' => $this->employee_id,
                ],
                [
                    'total_time' => $this->total_time,
                ]
            );
        });
    }

    public function unAprove()
    {
        $this->aprover_id = null;
        $this->status = DowntimeStatuses::Pending;

        $this->saveQuietly();

        $this->removeFromProduction();
    }

    public function removeFromProduction()
    {
        Production::query()
            ->whereDate('date', $this->date)
            ->where('campaign_id', $this->campaign_id)
            ->where('employee_id', $this->employee_id)
            ->first()
            ?->forceDelete();

    }
}
