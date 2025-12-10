<?php

namespace App\Models;

use App\Models\Traits\HasManyComments;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCampaign;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\BelongsToDowntimeReason;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Downtime extends Model
{
    /** @use HasFactory<\Database\Factories\DowntimeFactory> */
    use HasFactory;
    use SoftDeletes;
    use BelongsToEmployee;
    use BelongsToCampaign;
    use BelongsToDowntimeReason;
    use HasManyComments;
    use HasUuids;

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

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function aprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprover_id');
    }
}
